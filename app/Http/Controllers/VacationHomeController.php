<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacationHouse;
use App\Models\UserHasVacationHouse;

class VacationHomeController extends Controller
{
  function createVacationHome(Request $request)
  {
    $data = $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'price' => ['required', 'numeric', 'min:0'],
      'description' => ['nullable', 'string', 'max:500'],
      'images.*' => ['image', 'max:2048'],
    ]);

    $vacationHome = new VacationHouse();
    $vacationHome->name = $data['name'];
    $vacationHome->price = $data['price'];
    $vacationHome->description = $data['description'] ?? '';

    $imagePaths = [];
if ($request->hasFile('images')) {
    foreach ($request->file('images') as $image) {
        $randomName = uniqid() . '_' . bin2hex(random_bytes(5)) . '.' . $image->getClientOriginalExtension();
        $imagePaths[] = $image->storeAs('images', $randomName, 'public');
    }
}
    $vacationHome->images = $imagePaths;
    $vacationHome->save();

    return redirect('/vacation-homes')->with('success', 'Vacation home created successfully!');
  }

  public function index()
  {
    $vacationHomes = VacationHouse::all();
    return response()->json($vacationHomes);
  }

  public function show($id)
  {
    $vacationHome = VacationHouse::findOrFail($id);
    return response()->json($vacationHome);
  }

  public function rent(Request $request)
  {
      $user = $request->user();

      $data = $request->validate([
          'vacation_home_id' => ['required'],
          'start_date' => ['required', 'date', 'after_or_equal:today'],
          'end_date' => ['required', 'date', 'after_or_equal:start_date'],
          'number_of_guests' => ['required', 'integer', 'min:1'],
      ]);

      $vacationHome = VacationHouse::findOrFail($data['vacation_home_id']);

      $userHasVacationHome = new UserHasVacationHouse();
      $userHasVacationHome->user_id = $user->id;
      $userHasVacationHome->vacation_house_id = $vacationHome->id;
      $userHasVacationHome->start_date = $data['start_date'];
      $userHasVacationHome->end_date = $data['end_date'];
      $userHasVacationHome->number_of_guests = $data['number_of_guests'];
      $userHasVacationHome->save();

      return response()->json(['message' => 'Vacation home rented successfully!']);
  }

  public function myVacationHomes(Request $request)
  {
    $user = $request->user();
    $myVacationHomes = UserHasVacationHouse::where('user_id', $user->id)->with('vacationHouse')->get();
    return response()->json($myVacationHomes);
  }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VacationHouse;

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
          $imagePaths[] = $image->store('images', 'public');
        }
    }
    $vacationHome->images = $imagePaths;
    $vacationHome->save();

    return redirect('/test')->with('success', 'Vacation home created successfully!');
  }

  public function index()
  {
    $vacationHomes = VacationHouse::all();
    return response()->json($vacationHomes);
  }
}
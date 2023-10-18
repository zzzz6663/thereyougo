<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\DetailsUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateFeaturesRequest;
use App\Models\Profile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\Splade\Facades\Toast;

class ProfileController extends Controller
{
  public function updateDetails(DetailsUpdateRequest $request)
  {
    $data = $request->validationData();
    unset($data['_method']);
    if (Profile::query()->where('user_id', Auth::user()->id)->update($data))
      Toast::success('Details updated successfully');
    else Toast::danger('Details not updated');
    return redirect()->route('profile.global');
  }

  public function updateFeatures(UpdateFeaturesRequest $request)
  {
    $data = $request->validationData();
    unset($data['_method']);
    if (Profile::query()->where('user_id', Auth::user()->id)->update($data))
      Toast::success('Details updated successfully');
    else Toast::danger('Details not updated');
    return redirect()->route('profile.global');
  }

  public function uploadImages(Request $request)
  {
    $validated = $request->validate([
      'avatar' => ['max:5000'],
      'pic_1' => ['max:5000'],
      'pic_2' => ['max:5000'],
      'pic_3' => ['max:5000'],
    ]);
    if(!$validated)
    {
      Toast::danger('Files you sent is bigger than 5mb');
      return redirect()->back();
    }
    try {
      $profile = Auth::user()->profile;
      foreach ($request->allFiles() as $key => $file) {
        $profile->clearMediaCollection($key);
        $profile->addMediaFromRequest($key)->toMediaCollection($key);
      }
      Toast::success('Images uploaded successfully');
    } catch(Exception $e) {
      Toast::danger('Images not uploaded');
    }
    return redirect()->route('profile.global');
  }

  public function notify() {
    Toast::success('URL Copied');
    return redirect()->back();
  }
}

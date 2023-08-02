<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Tenant\User;
use App\Traits\UUID;

class File extends Model
{
	use HasFactory, UUID;

	protected $guarded = ['id', 'uuid'];
	protected $appends = ['url'];

	//Remove old Image
	public function removeImage($model, $old_dps)
	{
		if (count($old_dps) > 0) {
			foreach ($old_dps as $old_dp) {
				$model->files()->detach($old_dp->id);
				delete_file($old_dp->id);
			}
		}
	}
	
	//Add New Image
	public function createImage($request, $type)
	{
		$ext = strtolower($request->image->extension());
		$name = explode('.', $request->image->getClientOriginalName())[0];
		$imageName = strtolower(Str::random(16)) . '.' . $request->image->extension();  
		$request->image->move(public_path('file_upload'), $imageName);
		
		$file = File::create([
			'name' => $name,
			'path' => $imageName,
			'extension' => $ext,
			'type' => $type,
			'status' => 1
		]);
		return $file;
	}
	
	//Relations
	public function users()
	{
		return $this->morphedByMany(User::class, 'fileable');
	}
	
	public function getUrlAttribute()
	{
		return url('/').'/public/file_upload/'.$this->path;
	}
}

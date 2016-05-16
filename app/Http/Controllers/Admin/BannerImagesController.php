<?php

namespace App\Http\Controllers\Admin;


use App\BannerImage;
use App\Http\Controllers\AdminController;
use App\Http\Requests\Admin\CreateBannerRequest;
use App\Utils\ImageUpload;

class BannerImagesController extends AdminController
{

    protected $bannerImage;
    protected $upload;


    public function __construct(BannerImage $bannerImage, ImageUpload $upload)
    {
        $this->bannerImage = $bannerImage;
        $this->upload = $upload;

    }

    public function index() {

        $data = $this->bannerImage->all();
        return view('admin.banner.index', compact('data'));
    }


    public function create() {
        return view('admin.banner.create');
    }


    public function store(CreateBannerRequest $request) {
        $input = $request->all();

        if ($request->hasFile('image'))
            $input['image'] = $this->upload->process($request->file('image'), 'banners');

        $this->bannerImage->create($input);
        return redirect()->route('admin.banners.index')->with('message', 'Successfully created');

    }


    public function destroy($id) {
        $data = $this->bannerImage->find($id);
        $data->delete();
        return redirect()->route('admin.banners.index')->with('message', 'Successfully deleted');
    }

}
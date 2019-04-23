<?php

namespace craigdavey\filemanger;

use Illuminate\Http\Request;

class FilemanagerController extends Controller
{

    private $folders_files = array();

    function __construct(Request $request)
    {

        $this->get_folders_files($request->input('dir'));

    }

    function index(){

        return view('filemanager::index');

    }

    function getMarkup(){

        $data = array(
            'html' => view('filemanager::markup')->render()
        );

        return response()->json($data);

    }

    function get(){

        return response()->json($this->folders_files);

    }

    function get_folders_files($dir){

        if($dir == NULL){
            $dir = config('filemanager.uploads_folder').'/uploads';
        }else{
            $dir = $dir;
        }

        if(!file_exists($dir)){
            mkdir($dir);
        }

        $this->folders_files['dirs'] = array();
        $this->folders_files['file'] = array();

        $scandir = scandir($dir);

        $f = -1;
        $d = -1;

        foreach($scandir as $key => $value){

            if(
                $dir == config('filemanager.uploads_folder').'/uploads'
                ||
                $dir == config('filemanager.uploads_folder').'/uploads/*/..'
            ){

                if($value != '..' && $value != '.'){

                    if(is_file($dir.'/'.$value)){

                        $f++;

                        $this->folders_files['file'][$f]['path'] = $dir.'/'.$value;
                        $this->folders_files['file'][$f]['url'] = url(str_replace(config('filemanager.uploads_folder') , '' , $dir) . '/' . $value);
                        $this->folders_files['file'][$f]['basename'] = basename($dir.'/'.$value);

                    }elseif(is_dir($dir.'/'.$value)){

                        $d++;

                        $this->folders_files['dirs'][$d]['path'] = $dir.'/'.$value;
                        $this->folders_files['dirs'][$d]['url'] = url(str_replace(config('filemanager.uploads_folder') , '' , $dir) . '/' . $value);
                        $this->folders_files['dirs'][$d]['basename'] = basename($dir.'/'.$value);

                    }

                }

            }else{

                if($value != '.') {

                    if (is_file($dir . '/' . $value)) {

                        $f++;

                        $this->folders_files['file'][$f]['path'] = $dir . '/' . $value;
                        $this->folders_files['file'][$f]['url'] = url(str_replace(config('filemanager.uploads_folder') , '' , $dir) . '/' . $value);
                        $this->folders_files['file'][$f]['basename'] = basename($dir . '/' . $value);

                    }elseif (is_dir($dir . '/' . $value)) {

                        $d++;

                        if($value == '..'){

                            $this->folders_files['dirs'][$d]['path'] = dirname($dir . '/');
                            $this->folders_files['dirs'][$d]['url'] = dirname(url('/uploads/'));
                            $this->folders_files['dirs'][$d]['basename'] = basename($dir . '/' . $value);

                        }else{

                            $this->folders_files['dirs'][$d]['path'] = $dir . '/' . $value;
                            $this->folders_files['dirs'][$d]['url'] = url('/uploads/' . $value);
                            $this->folders_files['dirs'][$d]['basename'] = basename($dir . '/' . $value);

                        }


                    }

                }

            }

        }

    }

    function upload(Request $request){

        $current_dir = ($request->input('current_dir') != '') ? $request->input('current_dir') : config('filemanager.uploads_folder').'/uploads';
        $file = $request->file('upload_file');
        $pathname = $file->getPathname();

        $file->move($current_dir , $file->getClientOriginalName());

        if($request->input('current_dir') == NULL){
            $dir = config('filemanager.uploads_folder').'/uploads';
        }else{
            $dir = $request->input('current_dir');
        }

        $url = url(str_replace(config('filemanager.uploads_folder') , '' , $dir) . '/' . $file->getClientOriginalName());

        echo $url;

    }

}

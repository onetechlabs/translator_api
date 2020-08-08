<?php

namespace App\Http\Controllers;

use App\Languagelist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;

class TranslatorController extends Controller
{
    public function __construct(Request $request){
        $this->perpage = 10;
        $this->page = (null !== $request->input("page"))? (int)$request->input("page"):1;
        $this->spage = ($this->page > 1) ? ($this->page * $this->perpage) - $this->perpage : 0;
    }

    public function languageList(Request $request) {
      $language_lists = Languagelist::orderBy('id', 'desc')->get();
      $total_language_list = Languagelist::all()->count();
      $out = [
          "message" => "Success",
          "data"    => [
            "total_data"=>$total_language_list,
            "records"=>$language_lists
          ],
          "code"    => 200
      ];
      return response()->json($out, $out['code']);
    }

    public function translateResults(Request $request) {
      $lang_code_origin = $request->input("lang_id_origin");
      $lang_code_translated = $request->input("lang_id_translated");
      $text = str_replace(" ","%20",$request->input("text"));
      if($lang_code_origin!="" && $lang_code_translated!="" && $text!=""){
        $lang_code_origin_name=Languagelist::where("id",$lang_code_origin)->get();
        $lang_code_translated_name=Languagelist::where("id",$lang_code_translated)->get();
        $response = file_get_contents("https://translate.googleapis.com/translate_a/single?client=gtx&sl=".$lang_code_origin_name[0]->language_code."&tl=".$lang_code_translated_name[0]->language_code."&dt=t&q=".$text."");
        $translate = explode("\"",$response);
        $lang_origin = $translate[3];
        $lang_translated = $translate[1];

        $out = [
            "message" => "Success",
            "data"    => [
              "lang_origin"=>$lang_code_origin_name[0]->language_name,
              "lang_translated"=>$lang_code_translated_name[0]->language_name,
              "text_before"=>$lang_origin,
              "text_before_after"=>$lang_translated
            ],
            "code"    => 200
        ];
      } else {
        $out = [
            "message" => "Please, Fill Lang Code & Text!",
            "code"   => 500,
        ];
      }

      return response()->json($out, $out['code']);
    }

    /*STANDARD CRUD*/

    // Language List
    public function language_lists(Request $request){
        $language_lists = Languagelist::limit($this->perpage)->offset($this->spage)->orderBy('id', 'desc')->get();
        $total_language_list = Languagelist::all()->count();
        $total_page = ceil($total_language_list / $this->perpage);
        $out = [
            "message" => "Success",
            "data"    => [
              "total_page"=>$total_page,
              "total_data"=>$total_language_list,
              "current_page" => $this->page,
              "records"=>$language_lists
            ],
            "code"    => 200
        ];
        return response()->json($out, $out['code']);
    }

    public function language_listShow(Request $request, $id){
        $total_language_list = Languagelist::where("id",$id)->count();
        if($total_language_list !==0){
          $language_list = Languagelist::where("id",$id)->get();
          $out = [
              "message" => "Success",
              "data"    => [
                "record"=> $language_list
              ],
              "code"    => 200
          ];
        }else{
          $out = [
              "message" => "Failed to Language List",
              "code"   => 500,
          ];
        }
        return response()->json($out, $out['code']);
    }

    public function language_listCreate(Request $request){
        $validate=\Validator::make($request->all(),
        array(
          'language_code' => 'required|min:2|max:2',
          'language_name' => 'required|min:5'
        ));

        if ($validate->fails()) {
            $out = [
                "message" => $validate->messages(),
                "code"    => 500
            ];
            return response()->json($out, $out['code']);
        }

        $language_code = $request->input("language_code");
        $language_name = $request->input("language_name");

        $data = [
          "language_code" => $language_code,
          "language_name" => $language_name,
        ];

        if (Languagelist::create($data)) {
            $out = [
                "message" => "Success",
                "code"    => 200,
            ];
        } else {
            $out = [
                "message" => "Failed to Language List",
                "code"   => 500,
            ];
        }

        return response()->json($out, $out['code']);
    }

    public function language_listUpdate(Request $request, $id){
        $total_language_list = Languagelist::where("id",$id)->count();
        if($total_language_list !==0){
          $validate=\Validator::make($request->all(),
          array(
            'language_code' => 'required|min:2|max:2',
            'language_name' => 'required|min:5'
          ));

          if ($validate->fails()) {
              $out = [
                  "message" => $validate->messages(),
                  "code"    => 500
              ];
              return response()->json($out, $out['code']);
          }

          $language_code = $request->input("language_code");
          $language_name = $request->input("language_name");

          $language_list = Languagelist::find($id);
          $language_list->language_code = $language_code;
          $language_list->language_name = $language_name;
          $language_list->save();

          $out = [
              "message" => "Success",
              "code"    => 200,
          ];
        }else{
          $out = [
              "message" => "Failed to Language List",
              "code"   => 500,
          ];
        }
        return response()->json($out, $out['code']);
    }

    public function language_listDelete(Request $request, $id){
        $total_language_list = Languagelist::where("id",$id)->count();
        if($total_language_list !==0){
          $language_list = Languagelist::find($id);
          $language_list->delete();
          $out = [
              "message" => "Success",
              "code"    => 200,
          ];
        }else{
          $out = [
              "message" => "Failed to Language List",
              "code"   => 500,
          ];
        }
        return response()->json($out, $out['code']);
    }

}

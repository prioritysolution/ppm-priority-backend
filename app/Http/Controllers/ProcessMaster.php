<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Exception;
use Session;
use DB;
use \stdClass;

class ProcessMaster extends Controller
{
    public function post_unit(Request $request){
        $validator = Validator::make($request->all(),[
            'unit_name' =>'required',
            'org_id' => 'required'
        ]);
        if($validator->passes()){
        try {
              $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
              if(!$sql){
                throw new Exception;
              }
              $org_schema = $sql[0]->db;
              $db = Config::get('database.connections.mysql');
              $db['database'] = $org_schema;
              config()->set('database.connections.petro', $db);
              DB::connection('petro')->beginTransaction();
              
              $sql = DB::connection('petro')->statement("Call USP_PROCESS_UNIT(?,?,?,?,@err,@errmsg);",[null,$request->unit_name,auth()->user()->Id,1]);
              if(!$sql){
                DB::connection('petro')->rollBack();
                throw new Exception;
              }
              $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
              $error = $result[0]->Error_No;
              $msg = $result[0]->Err_Mesage;
              if($error<0){
                DB::connection('petro')->rollBack();
                return response()->json([
                    'status'=>400,
                    'message' => $msg
                ],400);
              }
              else{
                DB::connection('petro')->commit();
                return response()->json([
                    'status'=>200,
                    'message' => 'Unit Create Successful'
                ],200);
              }

            } catch (Exception $ex) {
                DB::connection('petro')->rollBack();
                $response = response()->json([
                    'message' => 'Error Found',
                    'status'=>400,
                    'details' => $ex->getmessage(),
                ],400);
    
                throw new HttpResponseException($response);
            }
        }
        else{
        $errors = $validator->errors();

        $response = response()->json([
          'message' => 'Invalid data send',
          'status'=>400,
          'details' => $errors->messages(),
      ],400);
  
      throw new HttpResponseException($response);
        }
    }
    public function get_unit(Int $org_id){
        try {
            $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
            if(!$sql){
              throw new Exception;
            }
            $org_schema = $sql[0]->db;
            $db = Config::get('database.connections.mysql');
            $db['database'] = $org_schema;
            config()->set('database.connections.petro', $db);
            
            
            $sql = DB::connection('petro')->select("Select Id,Unit_Name From mst_unit Where Is_Active=1;");
            if(!$sql){
              throw new Exception;
            }
            return response()->json([
                'messsage' =>'Data Found',
                'status'=>200,
                'Data' => $sql
            ],200);


          } catch (Exception $ex) {
              DB::connection('petro')->rollBack();
              $response = response()->json([
                  'message' => 'Error Found',
                  'status'=>400,
                  'details' => $ex->getmessage(),
              ],400);
  
              throw new HttpResponseException($response);
          }

    }
    public function update_unit(Request $request){
        $validator = Validator::make($request->all(),[
            'unit_name' =>'required',
            'org_id' => 'required',
            'unit_id' => 'required'
        ]);
        if($validator->passes()){
        try {
              $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
              if(!$sql){
                throw new Exception;
              }
              $org_schema = $sql[0]->db;
              $db = Config::get('database.connections.mysql');
              $db['database'] = $org_schema;
              config()->set('database.connections.petro', $db);
              DB::connection('petro')->beginTransaction();
              
              $sql = DB::connection('petro')->statement("Call USP_PROCESS_UNIT(?,?,?,?,@err,@errmsg);",[$request->unit_id,$request->unit_name,auth()->user()->Id,2]);
              if(!$sql){
                DB::connection('petro')->rollBack();
                throw new Exception;
              }
              $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
              $error = $result[0]->Error_No;
              $msg = $result[0]->Err_Mesage;
              if($error<0){
                DB::connection('petro')->rollBack();
                return response()->json([
                    'status'=>400,
                    'message' => $msg
                ],400);
              }
              else{
                DB::connection('petro')->commit();
                return response()->json([
                    'status'=>200,
                    'message' => 'Unit Update Successful'
                ],200);
              }

            } catch (Exception $ex) {
                DB::connection('petro')->rollBack();
                $response = response()->json([
                    'message' => 'Error Found',
                    'status'=>200,
                    'details' => $ex->getmessage(),
                ],400);
    
                throw new HttpResponseException($response);
            }
        }
        else{
        $errors = $validator->errors();

        $response = response()->json([
          'message' => 'Invalid data send',
          'status'=>200,
          'details' => $errors->messages(),
      ],400);
  
      throw new HttpResponseException($response);
        } 
    }
    public function post_cat(Request $request){
        $validator = Validator::make($request->all(),[
            'cat_name' =>'required',
            'org_id' => 'required'
        ]);
        if($validator->passes()){
        try {
              $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
              if(!$sql){
                throw new Exception;
              }
              $org_schema = $sql[0]->db;
              $db = Config::get('database.connections.mysql');
              $db['database'] = $org_schema;
              config()->set('database.connections.petro', $db);
              DB::connection('petro')->beginTransaction();
              
              $sql = DB::connection('petro')->statement("Call USP_PROCESS_CATAGARY(?,?,?,?,@err,@errmsg);",[null,$request->cat_name,auth()->user()->Id,1]);
              if(!$sql){
                DB::connection('petro')->rollBack();
                throw new Exception;
              }
              $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
              $error = $result[0]->Error_No;
              $msg = $result[0]->Err_Mesage;
              if($error<0){
                DB::connection('petro')->rollBack();
                return response()->json([
                    'status'=>400,
                    'message' => $msg
                ],400);
              }
              else{
                DB::connection('petro')->commit();
                return response()->json([
                    'status'=>200,
                    'message' => 'Catagary Create Successful'
                ],200);
              }

            } catch (Exception $ex) {
                DB::connection('petro')->rollBack();
                $response = response()->json([
                    'message' => 'Error Found',
                    'status'=>400,
                    'details' => $ex->getmessage(),
                ],400);
    
                throw new HttpResponseException($response);
            }
        }
        else{
        $errors = $validator->errors();

        $response = response()->json([
          'message' => 'Invalid data send',
          'status'=>400,
          'details' => $errors->messages(),
      ],400);
  
      throw new HttpResponseException($response);
        }
    }
    public function get_cat(Int $org_id){
        try {
            $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
            if(!$sql){
              throw new Exception;
            }
            $org_schema = $sql[0]->db;
            $db = Config::get('database.connections.mysql');
            $db['database'] = $org_schema;
            config()->set('database.connections.petro', $db);
            
            
            $sql = DB::connection('petro')->select("Select Id,Catagary_Name From mst_item_catagary Where Is_Active=1;");
            if(!$sql){
              throw new Exception;
            }
            return response()->json([
                'messsage' =>'Data Found',
                'status'=>200,
                'Data' => $sql
            ],200);


          } catch (Exception $ex) {
              DB::connection('petro')->rollBack();
              $response = response()->json([
                  'message' => 'Error Found',
                  'status'=>400,
                  'details' => $ex->getmessage(),
              ],400);
  
              throw new HttpResponseException($response);
          }
    }
    public function update_cat(Request $request){
        $validator = Validator::make($request->all(),[
            'cat_name' =>'required',
            'org_id' => 'required',
            'cat_id' => 'required'
        ]);
        if($validator->passes()){
        try {
              $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
              if(!$sql){
                throw new Exception;
              }
              $org_schema = $sql[0]->db;
              $db = Config::get('database.connections.mysql');
              $db['database'] = $org_schema;
              config()->set('database.connections.petro', $db);
              DB::connection('petro')->beginTransaction();
              
              $sql = DB::connection('petro')->statement("Call USP_PROCESS_CATAGARY(?,?,?,?,@err,@errmsg);",[$request->cat_id,$request->cat_name,auth()->user()->Id,2]);
              if(!$sql){
                DB::connection('petro')->rollBack();
                throw new Exception;
              }
              $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
              $error = $result[0]->Error_No;
              $msg = $result[0]->Err_Mesage;
              if($error<0){
                DB::connection('petro')->rollBack();
                return response()->json([
                    'status'=>400,
                    'message' => $msg
                ],400);
              }
              else{
                DB::connection('petro')->commit();
                return response()->json([
                    'status'=>200,
                    'message' => 'Catagary Update Successful'
                ],200);
              }

            } catch (Exception $ex) {
                DB::connection('petro')->rollBack();
                $response = response()->json([
                    'message' => 'Error Found',
                    'status'=>400,
                    'details' => $ex->getmessage(),
                ],400);
    
                throw new HttpResponseException($response);
            }
        }
        else{
        $errors = $validator->errors();

        $response = response()->json([
          'message' => 'Invalid data send',
          'status'=>400,
          'details' => $errors->messages(),
      ],400);
  
      throw new HttpResponseException($response);
        } 
    }
    public function dropdown_unit(Int $org_id){
      try {
            $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
              if(!$sql){
                throw new Exception;
              }
              $org_schema = $sql[0]->db;
              $db = Config::get('database.connections.mysql');
              $db['database'] = $org_schema;
              config()->set('database.connections.petro', $db);
              $sql = DB::connection('petro')->select("Call USP_GETDROPDOWN_VALUE(?);",[1]);
              if(!$sql){
                throw new Exception;
              }
              return response()->json([
                'message' => 'Data Found',
                'status'=>200,
                'Data' => $sql
              ]);

      } catch (Exception $ex) {
        $response = response()->json([
          'message' => 'Error Found',
          'status'=>400,
          'details' => $ex->getmessage(),
      ],400);

      throw new HttpResponseException($response);
      }
    }
    public function dropdown_cat(Int $org_id){
      try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          $sql = DB::connection('petro')->select("Call USP_GETDROPDOWN_VALUE(?);",[2]);
          if(!$sql){
            throw new Exception;
          }
          return response()->json([
            'message' => 'Data Found',
            'status'=>200,
            'Data' => $sql
          ]);

  } catch (Exception $ex) {
    $response = response()->json([
      'message' => 'Error Found',
      'status'=>400,
      'details' => $ex->getmessage(),
  ],400);

  throw new HttpResponseException($response);
  }
    }
    public function item_process(Request $request){
      $validator = Validator::make($request->all(),[
        'item_name' =>'required',
        'item_sh_name' =>'required',
        'item_type' => 'required',
        'unit_val' => 'required',
        'item_unit' => 'required',
        'cgst_val' =>'required',
        'sgst_val' => 'required',
        'igst_val' => 'required',
        'basic_sale_rate' => 'required',
        'remember_qnty' => 'required',
        'item_sale_gl' =>'required',
        'item_pur_gl' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          if($request->item_sale_gl!=$request->item_pur_gl){
            $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
            if(!$sql){
              throw new Exception;
            }
            $org_schema = $sql[0]->db;
            $db = Config::get('database.connections.mysql');
            $db['database'] = $org_schema;
            config()->set('database.connections.petro', $db);
            DB::connection('petro')->beginTransaction();
            
            $sql = DB::connection('petro')->statement("Call USP_POST_ITEM(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->item_name,$request->item_sh_name,$request->item_type,$request->unit_val,$request->item_unit,$request->cgst_val,$request->sgst_val,$request->igst_val,$request->basic_sale_rate,$request->remember_qnty,$request->open_qnty,$request->open_rate,$request->item_sale_gl,$request->item_pur_gl,auth()->user()->Id,1]);
            if(!$sql){
              DB::connection('petro')->rollBack();
              throw new Exception;
            }
            $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
            $error = $result[0]->Error_No;
            $msg = $result[0]->Err_Mesage;
            if($error<0){
              DB::connection('petro')->rollBack();
              return response()->json([
                  'status'=>400,
                  'message' => $msg
              ],400);
            }
            else{
              DB::connection('petro')->commit();
              return response()->json([
                  'status'=>200,
                  'message' => 'Item Create Successful'
              ],200);
            }
          }
          else{
            return response()->json([
              'status'=>400,
              'message' => 'Item Sale And Purchase Ledger Cannot Be Same !'
          ],400);
          }
          

        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status'=>400,
                'details' => $ex->getmessage(),
            ],400);

            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();

    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);

  throw new HttpResponseException($response);
    }
    }
  public function get_item_list(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select m.Id,m.Item_Name,u.Unit_Name From mst_item_Master m join mst_unit u on u.Id=m.Item_Unit;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function process_tank(Request $request){
    $validator = Validator::make($request->all(),[
      'Tank_Name' =>'required',
      'Tank_Diameter' =>'required',
      'Tank_Length' => 'required',
      'Max_Volum' => 'required',
      'Link_Item' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_TANK(?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Tank_Name,$request->Tank_Diameter,$request->Tank_Length,$request->Max_Volum,$request->Link_Item,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
               'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
              'status'=>200,
              'message' => 'Tank Added Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_tank(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select t.Id,t.Tank_Name,m.Item_Name,t.Tank_Diameter,t.Tank_Length,t.Max_Volum,t.Link_Item From mst_tank t join mst_item_Master m on m.Id=t.Link_Item Where Is_Active=1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function update_tank(Request $request){
    $validator = Validator::make($request->all(),[
      'Tank_Id' => 'required',
      'Tank_Name' =>'required',
      'Tank_Diameter' =>'required',
      'Tank_Length' => 'required',
      'Max_Volum' => 'required',
      'Link_Item' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_TANK(?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->Tank_Id,$request->Tank_Name,$request->Tank_Diameter,$request->Tank_Length,$request->Max_Volum,$request->Link_Item,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Tank Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  } 
  }
  public function convertToObject($array) {
    $object = new stdClass();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = $this->convertToObject($value);
        }
        $object->$key = $value;
    }
    return $object;
}
  public function process_pump(Request $request){
    $validator = Validator::make($request->all(),[
      'Pump_Name' => 'required',
      'No_Nozzel' =>'required',
      'Nozzel_Data' =>'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $temp_table_Drop = DB::connection('petro')->statement("Drop Temporary Table If Exists TempNozzelData;");
        $temp_Table_Create = DB::connection('petro')->statement("Create Temporary Table TempNozzelData
        (
          Id					Int Primary Key Auto_Increment,
            Temp_Nozzel_Id		Int,
            Temp_Pump_Id		Int,
            Temp_Nozzel_Name	Varchar(100),
            Temp_Tank_Id		Int
        );");

        if(!$temp_Table_Create){
          throw new Exception;
        }
        $array = $this->convertToObject($request->Nozzel_Data);
      
        foreach ($array as $nozzle_data) {
          $insert_Data = DB::connection('petro')->statement("Insert Into TempNozzelData(Temp_Nozzel_Id,Temp_Pump_Id,Temp_Nozzel_Name,Temp_Tank_Id) Values (?,?,?,?);",[null,null,$nozzle_data->nozzleName,$nozzle_data->tankId]);
        }

        $sql = DB::connection('petro')->statement("Call USP_POST_PUMP_NOZZEL(?,?,?,?,?,@err,@errmsg);",[null,$request->Pump_Name,$request->No_Nozzel,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Pump Add Successful'
          ],200);
        }


      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  } 
  }
  public function get_pump(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Pump_Name,No_Of_Nozzle From mst_pump Where Is_Active=1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function get_nozzle_data(Int $org_id,Int $pump_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select m.Id,m.Nozzle_Name,m.Link_Tank,t.Tank_Name From mst_nozzle m join mst_tank t on t.Id=m.Link_Tank Where m.Link_Pump=?;",[$pump_id]);
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function process_shift(Request $request){
    $validator = Validator::make($request->all(),[
      'Shift_Name' => 'required',
      'Shift_Start' =>'required',
      'Shift_End' =>'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_SHIFT(?,?,?,?,?,?,@err,@errmsg);",[null,$request->Shift_Name,$request->Shift_Start,$request->Shift_End,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Shift Add Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function update_shift(Request $request){
    $validator = Validator::make($request->all(),[
      'Shift_Id' => 'required',
      'Shift_Name' => 'required',
      'Shift_Start' =>'required',
      'Shift_End' =>'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_SHIFT(?,?,?,?,?,?,@err,@errmsg);",[$request->Shift_Id,$request->Shift_Name,$request->Shift_Start,$request->Shift_End,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Shift Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_shift(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Shift_Name,Shift_Start_Time,Shift_End_Time From mst_shift_details Where Is_Active=1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function get_acct_main_head(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Main_Head_Name,Main_Head_Code From mst_acct_main_head;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function process_acct_head(Request $request){
    $validator = Validator::make($request->all(),[
      'Head_Name' => 'required',
      'Head_Code' =>'required',
      'Main_Head' =>'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_ACCT_HEAD(?,?,?,?,?,?,@err,@errmsg);",[null,$request->Head_Name,$request->Head_Code,$request->Main_Head,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Account Head Add Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_acct_head_list(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select m.Id,m.Head_Name,m.Head_Code,m.Main_Head,h.Main_Head_Name From mst_acct_head m join mst_acct_main_head h on h.Id=m.Main_Head;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function update_acct_head(Request $request){
    $validator = Validator::make($request->all(),[
      'Head_Id' => 'required',
      'Head_Name' => 'required',
      'Head_Code' =>'required',
      'Main_Head' =>'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_ACCT_HEAD(?,?,?,?,?,?,@err,@errmsg);",[$request->Head_Id,$request->Head_Name,$request->Head_Code,$request->Main_Head,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Account Head Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function process_acct_ledg(Request $request){
    $validator = Validator::make($request->all(),[
      'Acct_Name' => 'required',
      'Acct_Code' => 'required',
      'Acct_Head' =>'required',
      'Open_Bal' =>'required',
      'Open_Date' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_ACCT_LEDGER(?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Acct_Name,$request->Acct_Code,$request->Acct_Head,$request->Open_Bal,$request->Open_Date,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Account Ledger Add Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_acct_ledg(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select m.Id,m.Acct_Name,m.Account_Code,m.Acct_Head,h.Head_Name,m.Open_Bal,m.Open_Date From mst_acct_ledger m join mst_acct_head h on h.Id=m.Acct_Head;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status'=>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function update_acct_ledg(Request $request){
    $validator = Validator::make($request->all(),[
      'Acct_Id' => 'required',
      'Acct_Name' => 'required',
      'Acct_Code' => 'required',
      'Acct_Head' =>'required',
      'Open_Bal' =>'required',
      'Open_Date' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_ACCT_LEDGER(?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->Acct_Id,$request->Acct_Name,$request->Acct_Code,$request->Acct_Head,$request->Open_Bal,$request->Open_Date,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Account Ledger Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function process_bank_acct(Request $request){
    $validator = Validator::make($request->all(),[
      'Bank_Name' => 'required',
      'Bank_Branch' => 'required',
      'Bank_IFSC' =>'required',
      'Account_No' =>'required',
      'Link_GL' => 'required',
      'Open_Date' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_POST_BANK_ACCT(?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Bank_Name,$request->Bank_Branch,$request->Bank_IFSC,$request->Account_No,$request->Link_GL,$request->Open_Bal,$request->Open_Date,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
            'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
            'status'=>200,
              'message' => 'Bank Account Add Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_bank_acct(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select m.Id,m.Bank_Name,m.Bank_Branch,m.Bank_IFSC,m.Bank_Account_No,m.Under_Gl,l.Acct_Name,m.Open_Bal,m.Open_Date From mst_bank_acct m join mst_acct_ledger l on l.Id=m.Under_Gl;");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status'=>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function update_bank_acct(Request $request){
    $validator = Validator::make($request->all(),[
        'Bank_Id' => 'required',
        'Bank_Name' => 'required',
        'Bank_Branch' => 'required',
        'Bank_IFSC' =>'required',
        'Account_No' =>'required',
        'Link_GL' => 'required',
        'Open_Bal' => 'required',
        'Open_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_POST_BANK_ACCT(?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->Bank_Id,$request->Bank_Name,$request->Bank_Branch,$request->Bank_IFSC,$request->Account_No,$request->Link_GL,$request->Open_Bal,$request->Open_Date,auth()->user()->Id,2]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status'=>200,
                'message' => 'Bank Account Update Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status'=>400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function process_card(Request $request){
    $validator = Validator::make($request->all(),[
        'POS_Name' => 'required',
        'POS_Provider' => 'required',
        'POS_Type' =>'required',
        'Under_Bank' =>'required',
        'Install_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_ADD_POSCARD(?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->POS_Name,$request->POS_Provider,$request->POS_Type,$request->Under_Bank,$request->Install_Date,auth()->user()->Id,1]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'POS / CARD Add Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function get_pos_card(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select m.Id,m.Pos_Name,m.Pos_Provider,m.Pos_Type,m.Link_Bank,concat(b.Bank_Name,'-',b.Bank_Account_No) As bank_name,m.Installation_Date From mst_card_pos m join mst_bank_acct b on b.Id=m.Link_Bank;");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function get_bank_pos(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select Id,concat(Bank_Name,'-',Bank_Account_No) As Bank_Name From mst_bank_acct;");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function update_card(Request $request){
    $validator = Validator::make($request->all(),[
        'POS_Id' => 'required',
        'POS_Name' => 'required',
        'POS_Provider' => 'required',
        'POS_Type' =>'required',
        'Under_Bank' =>'required',
        'Install_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_ADD_POSCARD(?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->POS_Id,$request->POS_Name,$request->POS_Provider,$request->POS_Type,$request->Under_Bank,$request->Install_Date,auth()->user()->Id,2]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'POS / CARD Update Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function customer_process(Request $request){
    $validator = Validator::make($request->all(),[
        'Cust_Name' => 'required',
        'Cust_Address' => 'required',
        'Cust_Mobile' =>'required',
        'Max_Credit_Limit' => 'required',
        'Max_Credit_Days' => 'required',
        'Link_Gl' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_ADD_CUSTOMER(?,?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Cust_Name,$request->Cust_Address,$request->Cust_Mobile,$request->Cust_Mail,$request->Cust_GSTIN,$request->Max_Credit_Limit,$request->Max_Credit_Days,$request->Open_Bal,$request->Link_Gl,auth()->user()->Id,1]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Customer Add Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function get_customer(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select m.Id,m.Cust_Name,m.Cust_Addr,m.Cust_Mobile,m.Cust_Mail,m.Cust_GSTIN,m.Max_Limit,m.Max_Credit_Day,m.Open_Bal,m.Link_Gl,l.Acct_Name From mst_customer m  join  mst_acct_ledger l on l.Id=m.Link_Gl;");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function update_customer(Request $request){
    $validator = Validator::make($request->all(),[
        'Cust_Id' => 'required',
        'Cust_Name' => 'required',
        'Cust_Address' => 'required',
        'Cust_Mobile' =>'required',
        'Cust_Mail' =>'required',
        'Cust_GSTIN' => 'required',
        'Max_Credit_Limit' => 'required',
        'Max_Credit_Days' => 'required',
        'Open_Bal' => 'required',
        'Link_Gl' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_ADD_CUSTOMER(?,?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->Cust_Id,$request->Cust_Name,$request->Cust_Address,$request->Cust_Mobile,$request->Cust_Mail,$request->Cust_GSTIN,$request->Max_Credit_Limit,$request->Max_Credit_Days,$request->Open_Bal,$request->Link_Gl,auth()->user()->Id,2]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Customer Update Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function process_supplier(Request $request){
    $validator = Validator::make($request->all(),[
        'Suppl_Name' => 'required',
        'Suppl_Addr' => 'required',
        'Suppl_Mob' =>'required',
        'Max_Crd_Days' => 'required',
        'Link_Gl' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_ADD_SUPPLIER(?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Suppl_Name,$request->Suppl_Addr,$request->Suppl_Mob,$request->Suppl_Mail,$request->Suppl_GSTIN,$request->Max_Crd_Days,$request->Open_Bal,$request->Link_Gl,auth()->user()->Id,1]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Supplier Add Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function get_supplier(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select m.Id,m.Supp_Name,m.Supp_Add,m.Supp_Mobile,m.Supp_Mail,m.Supp_GSTIN,m.Max_Days,m.Open_Bal,m.Link_Gl,l.Acct_Name From mst_supplier m  join  mst_acct_ledger l on l.Id=m.Link_Gl;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function update_supplier(Request $request){
    $validator = Validator::make($request->all(),[
      'Suppl_Id' => 'required',
      'Suppl_Name' => 'required',
      'Suppl_Addr' => 'required',
      'Suppl_Mob' =>'required',
      'Suppl_Mail' =>'required',
      'Suppl_GSTIN' => 'required',
      'Max_Crd_Days' => 'required',
      'Open_Bal' => 'required',
      'Link_Gl' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_SUPPLIER(?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->Suppl_Id,$request->Suppl_Name,$request->Suppl_Addr,$request->Suppl_Mob,$request->Suppl_Mail,$request->Suppl_GSTIN,$request->Max_Crd_Days,$request->Open_Bal,$request->Link_Gl,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
              'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
              'status' =>200,
              'message' => 'Supplier Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' => 400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function process_tanker(Request $request){
    $validator = Validator::make($request->all(),[
      'Tanker_Name' => 'required',
      'Vechle_Name' => 'required',
      'Vechle_No' => 'required',
      'Capacity' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_TANKER(?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Tanker_Name,$request->Vechle_Name,$request->Vechle_No,$request->Capacity,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
              'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
              'status' =>200,
              'message' => 'Tanker Add Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' => 400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_tanker(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Tanker_Name,Vechle_Name,Vechle_No,Capacity From mst_tanker;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function update_tanker(Request $request){
    $validator = Validator::make($request->all(),[
      'Tanker_Id' => 'required',
      'Tanker_Name' => 'required',
      'Vechle_Name' => 'required',
      'Vechle_No' => 'required',
      'Capacity' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_TANKER(?,?,?,?,?,?,?,@err,@errmsg);",[$request->Tanker_Id,$request->Tanker_Name,$request->Vechle_Name,$request->Vechle_No,$request->Capacity,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
              'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
              'status' =>200,
              'message' => 'Tanker Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' => 400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_drp_staff_type(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Option_Id,Option_Name From mst_parameater Where Option_Group=1 And Is_Active=1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function get_drp_staff_deg(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Option_Id,Option_Name From mst_parameater Where Option_Group=2 And Is_Active=1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function process_staff(Request $request){
    $validator = Validator::make($request->all(),[
      'Staff_Name' => 'required',
      'Staff_Addr' => 'required',
      'Staff_Mob' => 'required',
      'Staff_mail' => 'required',
      'Staff_Type' => 'required',
      'Staff_Deg' => 'required',
      'Staff_Join' => 'required',
      'Staff_Basic' => 'required',
      'Staff_DA' => 'required',
      'Staff_HRA' => 'required',
      'Staff_TA' => 'required',
      'Staff_Misc' => 'required',
      'Staff_PF' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_STAFF(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Staff_Name,$request->Staff_Addr,$request->Staff_Mob,$request->Staff_mail,$request->Staff_Type,$request->Staff_Deg,$request->Staff_Join,$request->Staff_Basic,$request->Staff_DA,$request->Staff_HRA,$request->Staff_TA,$request->Staff_Misc,$request->Staff_PF,auth()->user()->Id,1]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
              'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
              'status' =>200,
              'message' => 'Staff Add Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' => 400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function get_staff(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Staff_Name,Staff_Addr,Staff_Mobile,Staff_Mail,Staff_Type,UDF_GET_OPTIONNAME(Staff_Type,1) As Type_Name,Staff_Deg,UDF_GET_OPTIONNAME(Staff_Deg,2) As Deg_Name,Join_Date,Basic_Pay,DA_Pay,HRA_Pay,TA_Pay,Misc_Pay,PF_Ded From mst_staff;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function update_staff(Request $request){
    $validator = Validator::make($request->all(),[
      'Staff_Id' => 'required',
      'Staff_Name' => 'required',
      'Staff_Addr' => 'required',
      'Staff_Mob' => 'required',
      'Staff_mail' => 'required|email',
      'Staff_Type' => 'required',
      'Staff_Deg' => 'required',
      'Staff_Join' => 'required',
      'Staff_Basic' => 'required',
      'Staff_DA' => 'required',
      'Staff_HRA' => 'required',
      'Staff_TA' => 'required',
      'Staff_Misc' => 'required',
      'Staff_PF' => 'required',
      'org_id' => 'required'
  ]);
  if($validator->passes()){
  try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        DB::connection('petro')->beginTransaction();
        
        $sql = DB::connection('petro')->statement("Call USP_ADD_STAFF(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,@err,@errmsg);",[$request->Staff_Id,$request->Staff_Name,$request->Staff_Addr,$request->Staff_Mob,$request->Staff_mail,$request->Staff_Type,$request->Staff_Deg,$request->Staff_Join,$request->Staff_Basic,$request->Staff_DA,$request->Staff_HRA,$request->Staff_TA,$request->Staff_Misc,$request->Staff_PF,auth()->user()->Id,2]);
        if(!$sql){
          DB::connection('petro')->rollBack();
          throw new Exception;
        }
        $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
        $error = $result[0]->Error_No;
        $msg = $result[0]->Err_Mesage;
        if($error<0){
          DB::connection('petro')->rollBack();
          return response()->json([
              'status'=>400,
              'message' => $msg
          ],400);
        }
        else{
          DB::connection('petro')->commit();
          return response()->json([
              'status' =>200,
              'message' => 'Staff Update Successful'
          ],200);
        }

      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' => 400,
              'details' => $ex->getmessage(),
          ],400);

          throw new HttpResponseException($response);
      }
  }
  else{
  $errors = $validator->errors();

  $response = response()->json([
    'message' => 'Invalid data send',
    'status'=>400,
    'details' => $errors->messages(),
],400);

throw new HttpResponseException($response);
  }
  }
  public function process_rate(Request $request){
    $validator = Validator::make($request->all(),[
        'Rate_Details' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          $array = $this->convertToObject($request->Rate_Details);
      
        foreach ($array as $nozzle_data) {
            $sql = DB::connection('petro')->statement("Call USP_POST_ITEM_RATE(?,?,?,?,?,?,@err,@errmsg);",[null,$nozzle_data->id,$nozzle_data->Item_Rate,$nozzle_data->rateDate,auth()->user()->Id,1]);
            if(!$sql){
              DB::connection('petro')->rollBack();
              throw new Exception;
            }
        }
          
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Rate Add Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function get_item_rate(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select m.Id,m.Item_Id,i.Item_Name,m.Item_Rate,m.Valid_Date From mst_item_rate m join mst_item_Master i on i.Id=m.Item_Id;");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function update_item_rate(Request $request){
    $validator = Validator::make($request->all(),[
        'Rate_Id' => 'required',
        'Item_Name' => 'required',
        'Item_Rate' => 'required',
        'Rate_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_POST_ITEM_RATE(?,?,?,?,?,?,@err,@errmsg);",[$request->Rate_Id,$request->Item_Name,$request->Item_Rate,$request->Rate_Date,auth()->user()->Id,2]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Rate Update Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function check_daily_rate(Request $request){
    $validator = Validator::make($request->all(),[
        'Check_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          
          $result = DB::connection('petro')->select("Call USP_CHECK_DAILY_RATE(?);",[$request->Check_Date]);
          $error = $result[0]->Error_No;
          $msg = $result[0]->Message;
          if($error<0){
            
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => $msg
            ],200);
          }
  
        } catch (Exception $ex) {
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function process_open_read(Request $request){
    $validator = Validator::make($request->all(),[
        'Pump_Id' => 'required',
        'Nozle_Id' => 'required',
        'Open_Read' => 'required',
        'Open_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_POST_OPN_READ(?,?,?,?,?,?,?,@err,@errmsg);",[null,$request->Pump_Id,$request->Nozle_Id,$request->Open_Read,$request->Open_Date,auth()->user()->Id,1]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Open Read Add Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function get_open_read(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select m.Id,m.Pump_Id,p.Pump_Name,m.Nozzle_Id,n.Nozzle_Name,m.Open_Read,m.Open_Date From opn_meter_val m join mst_pump p on p.Id=m.Pump_Id join mst_nozzle n on n.Id=Nozzle_Id
        Where m.Is_Active=1;");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function updare_open_read(Request $request){
    $validator = Validator::make($request->all(),[
        'Rate_Id' => 'required',
        'Pump_Id' => 'required',
        'Nozle_Id' => 'required',
        'Open_Read' => 'required',
        'Open_Date' => 'required',
        'org_id' => 'required'
    ]);
    if($validator->passes()){
    try {
          $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$request->org_id]);
          if(!$sql){
            throw new Exception;
          }
          $org_schema = $sql[0]->db;
          $db = Config::get('database.connections.mysql');
          $db['database'] = $org_schema;
          config()->set('database.connections.petro', $db);
          DB::connection('petro')->beginTransaction();
          
          $sql = DB::connection('petro')->statement("Call USP_POST_OPN_READ(?,?,?,?,?,?,?,@err,@errmsg);",[$request->Rate_Id,$request->Pump_Id,$request->Nozle_Id,$request->Open_Read,$request->Open_Date,auth()->user()->Id,2]);
          if(!$sql){
            DB::connection('petro')->rollBack();
            throw new Exception;
          }
          $result = DB::connection('petro')->select("Select @err As Error_No,@errmsg As Err_Mesage");
          $error = $result[0]->Error_No;
          $msg = $result[0]->Err_Mesage;
          if($error<0){
            DB::connection('petro')->rollBack();
            return response()->json([
                'status'=>400,
                'message' => $msg
            ],400);
          }
          else{
            DB::connection('petro')->commit();
            return response()->json([
                'status' =>200,
                'message' => 'Open Read Update Successful'
            ],200);
          }
  
        } catch (Exception $ex) {
            DB::connection('petro')->rollBack();
            $response = response()->json([
                'message' => 'Error Found',
                'status' => 400,
                'details' => $ex->getmessage(),
            ],400);
  
            throw new HttpResponseException($response);
        }
    }
    else{
    $errors = $validator->errors();
  
    $response = response()->json([
      'message' => 'Invalid data send',
      'status'=>400,
      'details' => $errors->messages(),
  ],400);
  
  throw new HttpResponseException($response);
    }
  }
  public function get_denom_param(){
    try {
        $sql = DB::select("Select Id,Label_Name,Note_Value From mst_cash_denom Where Is_Active=1;");

        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function get_rate_item(Int $org_id){
    try {
        $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
        if(!$sql){
          throw new Exception;
        }
        $org_schema = $sql[0]->db;
        $db = Config::get('database.connections.mysql');
        $db['database'] = $org_schema;
        config()->set('database.connections.petro', $db);
        
        
        $sql = DB::connection('petro')->select("Select Id,Item_Name From mst_item_Master Where Id in(Select Link_Item From mst_tank Where Is_Active=1);");
        if(!$sql){
          throw new Exception;
        }
        return response()->json([
            'messsage' =>'Data Found',
            'status'=>200,
            'Data' => $sql
        ],200);
  
  
      } catch (Exception $ex) {
          DB::connection('petro')->rollBack();
          $response = response()->json([
              'message' => 'Error Found',
              'status' =>400,
              'details' => $ex->getmessage(),
          ],400);
  
          throw new HttpResponseException($response);
      }
  }
  public function get_tank_item(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Item_Name From mst_item_Master Where Item_Catagory=1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }
  public function get_other_item(Int $org_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Item_Name From mst_item_Master Where Item_Catagory<>1;");
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }
  }

  public function get_cust_info(Int $org_id,Int $cust_id){
    try {
      $sql = DB::select("Select UDF_GET_ORG_SCHEMA(?) as db;",[$org_id]);
      if(!$sql){
        throw new Exception;
      }
      $org_schema = $sql[0]->db;
      $db = Config::get('database.connections.mysql');
      $db['database'] = $org_schema;
      config()->set('database.connections.petro', $db);
      
      
      $sql = DB::connection('petro')->select("Select Id,Cust_Name,Cust_Addr,Cust_Mobile,Cust_Mail,Cust_GSTIN From mst_customer Where Id=?;",[$cust_id]);
      if(!$sql){
        throw new Exception;
      }
      return response()->json([
          'messsage' =>'Data Found',
          'status'=>200,
          'Data' => $sql
      ],200);


    } catch (Exception $ex) {
        DB::connection('petro')->rollBack();
        $response = response()->json([
            'message' => 'Error Found',
            'status' =>400,
            'details' => $ex->getmessage(),
        ],400);

        throw new HttpResponseException($response);
    }


  }
}
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

class ProcessingProcess extends Controller
{
    public function get_read_data(Request $request){
        $validator = Validator::make($request->all(),[
            'Nozzle_Id' =>'required',
            'Read_Date' => 'required',
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
            
            
            $sql = DB::connection('petro')->select("Call USP_GET_METERREAD_DATA(?,?);",[$request->Read_Date,$request->Nozzle_Id]);
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
    public function get_gst_item_data(Request $request){
        $validator = Validator::make($request->all(),[
            'Item_Id' =>'required',
            'Read_Date' => 'required',
            'Item_Rate'=> 'required',
            'Item_Qnty'=>   'required',
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
            
            
            $sql = DB::connection('petro')->select("Call USP_GET_GSTIN_CALC(?,?,?,?);",[$request->Read_Date,$request->Item_Id,$request->Item_Rate,$request->Item_Qnty]);
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
    public function process_meter_read(Request $request){
        $validator = Validator::make($request->all(),[
            'pRead_Date' =>'required',
            'pShift_Id' => 'required',
            'pSTaff_Id'=> 'required',
            'pExces_Amt'=>   'required',
            'pNet_Round' => 'required',
            'pMeter_Read_Data' => 'required',
            'pCash_Data' => 'required',
            'pBank_Data' => 'required',
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

            $meter_temp_table_drop = DB::connection('petro')->statement("Drop Temporary Table If Exists tempreaddatda;");
            $meter_temp_table_create = DB::connection('petro')->statement("Create Temporary Table tempreaddatda
                (
                    Id				Int Primary Key auto_increment,
                    Pump_Id			Int,
                    Nozle_Id		Int,
                    Open_Read		Numeric(18,2),
                    Test_Qnty		Numeric(18,2),
                    CreadiT_Qnty	Numeric(18,2),
                    Close_Read		Numeric(18,2),
                    Item_Id			Int,
                    Item_Rate		Numeric(18,2),
                    Item_Gl			Int,
                    Tot_Amt			Numeric(18,2)
                );");

                if(!$meter_temp_table_create){
                    throw new Exception;
                }                

            $read_data = $request->pMeter_Read_Data;
            if(is_array($read_data)){
                $read_data = $this->convertToObject($request->pMeter_Read_Data);

                foreach ($read_data as $read_data) {
                    $meter_insert =  DB::connection('petro')->statement("Insert Into tempreaddatda (Pump_Id,Nozle_Id,Open_Read,Test_Qnty,CreadiT_Qnty,Close_Read,Item_Id,Item_Rate,Item_Gl,Tot_Amt) Values (?,?,?,?,?,?,?,?,?,?);",[$read_data->pump,$read_data->nozzle,$read_data->openingReading,$read_data->testing,$read_data->creditQuantity,$read_data->closingReading,$read_data->itemId,$read_data->itemRate,null,$read_data->totalAmount]);
                }

                if(!$meter_insert){
                    throw new Exception;
                }
            }
            $add_item_temptable_drop = DB::connection('petro')->statement("Drop Temporary Table If Exists TempAddItem;");
            $add_item_temptable_create = DB::connection('petro')->statement("Create Temporary Table TempAddItem
                    (
                        Id					Int Primary Key Auto_Increment,
                        Item_Id				Int,
                        Item_Unit			Int,
                        Item_Qnty			Int,
                        Item_Rate			Numeric(18,2),
                        Item_CGST			Numeric(18,2),
                        Item_SGST			Numeric(18,2),
                        Item_IGST			Numeric(18,2),
                        Round_Amt			Numeric(18,2),
                        Item_Gross_Amt		Numeric(18,2),
                        Item_Gl				Int,
                        Tot_Amount			Numeric(18,2)
                    );");
            
            if(!$add_item_temptable_create){
                throw new Exception;
            }

            $add_item = $request->pAdd_Item_Data;

            if(is_array($add_item)){
                $add_item = $this->convertToObject($request->pAdd_Item_Data);

                foreach ($add_item as $add_data) {
                    
                    $add_item_insert = DB::connection('petro')->statement(" Insert Into TempAddItem (Item_Id,Item_Qnty,Item_Rate,Item_CGST,Item_SGST,Item_IGST,Round_Amt,Item_Gross_Amt,Item_Gl,Tot_Amount) Values (?,?,?,?,?,?,?,?,?,?);",[$add_data->itemId,$add_data->itemQnty,$add_data->itemRate,$add_data->cgst,$add_data->sgst,$add_data->igst,$add_data->roundOff,$add_data->grossAmount,null,$add_data->totalAmount]);
                }
                
            }

            $cash_table_drop = DB::connection('petro')->statement("Drop Temporary Table If Exists tempcashData;");
            $cash_table_create = DB::connection('petro')->statement("Create Temporary Table tempcashData
                (
                    Id				Int Primary Key Auto_Increment,
                    Denom_Id		Int,
                    Denom_Value		Int,
                    Amount			Numeric(18,2)
                );");

                if(!$cash_table_create){
                    throw new Exception;
                }

            if(is_array($request->pCash_Data)){
                $cash_data = $this->convertToObject($request->pCash_Data);

                foreach ($cash_data as $cash_insert) {
                    $cash_table_insert = DB::connection('petro')->statement("Insert Into tempcashData (Denom_Id,Denom_Value,Amount) Values (?,?,?);",[$cash_insert->noteId,$cash_insert->denominator,$cash_insert->totalAmount]);
                }
                if(!$cash_table_insert){
                    throw new Exception;
                }
            }

            $bank_table_Drop = DB::connection('petro')->statement("Drop Temporary Table If Exists tempBankData;");
            $bank_table_create = DB::connection('petro')->statement("Create Temporary Table tempBankData
                (
                    Id				Int Primary Key Auto_Increment,
                    Pos_Id			Int,
                    Amount			Numeric(18,2)
                );");

            if(is_array($request->pBank_Data)){
                $bank_data = $this->convertToObject($request->pBank_Data);

                foreach ($bank_data as $bank_details) {
                   
                    $bank_table_insert = DB::connection('petro')->statement("Insert Into tempBankData (Pos_Id,Amount) Values (?,?);",[$bank_details->cardPosId,$bank_details->amount]);
                }
                if(!$bank_table_insert){
                    throw new Exception;
                }
            }

            $sp_call = DB::connection('petro')->statement("Call USP_POST_METER_READ_DATA (?,?,?,?,?,?,?,@err_no,@err_msg);",[$request->pRead_Date,$request->pShift_Id,$request->pSTaff_Id,$request->pExces_Amt,$request->pNet_Round,auth()->user()->Id,$request->org_id]);
            
            if(!$sp_call){
                DB::connection('petro')->rollBack();
                throw new Exception;
            }
            $result = DB::connection('petro')->select("Select @err_no As Error_No,@err_msg As Err_Message;");
            $error = $result[0]->Error_No;
            $msg = $result[0]->Err_Message;

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
                    'message' => 'Meter Reading Is Successfull !!'
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
}
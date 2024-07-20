<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiLogin;
use App\Http\Controllers\ProcessMaster;
use App\Http\Controllers\ProcessingProcess;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/admin/login',[ApiLogin::class,'Process_Login'])->middleware('api_access');

Route::group([
    'middleware' => ['auth:sanctum',]
],function(){
    Route::get('/admin/finyear/{org_id}',[ApiLogin::class,'get_finyear']);
    Route::get('/admin/dashboard',[ApiLogin::class,'get_dashboard']);
    Route::get('logout',[ApiLogin::class,'logout']);
// master setup route

    Route::post('/master/AddUnit',[ProcessMaster::class,'post_unit']);
    Route::get('/master/GetUnit/{org_id}',[ProcessMaster::class,'get_unit']);
    Route::put('/master/UpdateUnit',[ProcessMaster::class,'update_unit']);
    Route::post('/master/AddCatagary',[ProcessMaster::class,'post_cat']);
    Route::get('/master/GetCatagary/{org_id}',[ProcessMaster::class,'get_cat']);
    Route::put('/master/UpdateCatagary',[ProcessMaster::class,'update_cat']);
    Route::post('/master/AddItem',[ProcessMaster::class,'item_process']);
    Route::get('/master/GetItemList/{org_id}',[ProcessMaster::class,'get_item_list']);
    Route::get('/master/DropDownUnit/{org_id}',[ProcessMaster::class,'dropdown_unit']);
    Route::get('/master/DropDownCat/{org_id}',[ProcessMaster::class,'dropdown_cat']);
    Route::post('/master/AddTank',[ProcessMaster::class,'process_tank']);
    Route::get('/master/GetTank/{org_id}',[ProcessMaster::class,'get_tank']);
    Route::put('/master/UpdateTank',[ProcessMaster::class,'update_tank']);
    Route::post('/master/AddPump',[ProcessMaster::class,'process_pump']);
    Route::get('/master/GetPumpList/{org_id}',[ProcessMaster::class,'get_pump']);
    Route::get('/master/GetNozzle/{org_id}/{pump_id}',[ProcessMaster::class,'get_nozzle_data']);
    Route::post('/master/AddShift',[ProcessMaster::class,'process_shift']);
    Route::put('/master/UpdateShift',[ProcessMaster::class,'update_shift']);
    Route::get('/master/GetShift/{org_id}',[ProcessMaster::class,'get_shift']);
    Route::get('/master/GetAcctMain/{org_id}',[ProcessMaster::class,'get_acct_main_head']);
    Route::post('/master/AddAcctHead',[ProcessMaster::class,'process_acct_head']);
    Route::get('/master/GetAcctHead/{org_id}',[ProcessMaster::class,'get_acct_head_list']);
    Route::put('/master/UpdateAcctHead',[ProcessMaster::class,'update_acct_head']);
    Route::post('/master/AddAcctLedg',[ProcessMaster::class,'process_acct_ledg']);
    Route::get('/master/GetAcctLedg/{org_id}',[ProcessMaster::class,'get_acct_ledg']);
    Route::put('/master/UpdateAcctLedg',[ProcessMaster::class,'update_acct_ledg']);
    Route::post('/master/AddBankAcct',[ProcessMaster::class,'process_bank_acct']);
    Route::get('/master/GetBankAcct/{org_id}',[ProcessMaster::class,'get_bank_acct']);
    Route::put('/master/UpdateBankAcct',[ProcessMaster::class,'update_bank_acct']);
    Route::post('/master/AddCardPos',[ProcessMaster::class,'process_card']);
    Route::get('/master/GetCardPos/{org_id}',[ProcessMaster::class,'get_pos_card']);
    Route::get('/master/GetBankPos/{org_id}',[ProcessMaster::class,'get_bank_pos']);
    Route::put('/master/UpdateCardPos',[ProcessMaster::class,'update_card']);
    Route::post('/master/AddCustomer',[ProcessMaster::class,'customer_process']);
    Route::get('/master/GetCustomer/{org_id}',[ProcessMaster::class,'get_customer']);
    Route::put('/master/UpdateCustomer',[ProcessMaster::class,'update_customer']);
    Route::post('/master/AddSupplier',[ProcessMaster::class,'process_supplier']);
    Route::get('/master/GetSupplier/{org_id}',[ProcessMaster::class,'get_supplier']);
    Route::put('/master/UpdateSupplier',[ProcessMaster::class,'update_supplier']);
    Route::post('/master/AddTanker',[ProcessMaster::class,'process_tanker']);
    Route::get('/master/GetTanker/{org_id}',[ProcessMaster::class,'get_tanker']);
    Route::put('/master/UpdateTanker',[ProcessMaster::class,'update_tanker']);
    Route::get('/master/GetStaffType/{org_id}',[ProcessMaster::class,'get_drp_staff_type']);
    Route::get('/master/GetStaffDesignation/{org_id}',[ProcessMaster::class,'get_drp_staff_deg']);
    Route::post('/master/AddStaff',[ProcessMaster::class,'process_staff']);
    Route::get('/master/GetStaff/{org_id}',[ProcessMaster::class,'get_staff']);
    Route::put('/master/UpdateStaff',[ProcessMaster::class,'update_staff']);
    Route::post('/master/AddItemRate',[ProcessMaster::class,'process_rate']);
    Route::get('/master/GetItemRate/{org_id}',[ProcessMaster::class,'get_item_rate']);
    Route::put('/master/UpdateItemRate',[ProcessMaster::class,'update_item_rate']);
    Route::post('/master/CheckRateOpen',[ProcessMaster::class,'check_daily_rate']);
    Route::post('/master/PostOpenRead',[ProcessMaster::class,'process_open_read']);
    Route::get('/master/GetOpenRead/{org_id}',[ProcessMaster::class,'get_open_read']);
    Route::put('/master/UpdateOpenRead',[ProcessMaster::class,'updare_open_read']);
    Route::get('/master/getcashdenom',[ProcessMaster::class,'get_denom_param']);
    Route::get('/master/getRateItem/{org_id}',[ProcessMaster::class,'get_rate_item']);
    Route::get('/master/GetTankItem/{org_id}',[ProcessMaster::class,'get_tank_item']);
    Route::get('/master/GetOtherItem/{org_id}',[ProcessMaster::class,'get_other_item']);

    // processing process route

    Route::post('/processing/GetReadingData',[ProcessingProcess::class,'get_read_data']);
    Route::post('/processing/GetGSTData',[ProcessingProcess::class,'get_gst_item_data']);
    Route::post('/processing/ProcessMeterRead',[ProcessingProcess::class,'process_meter_read']);

});
<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * @SWG\Info(title="Network Inventory DEV", version="0.3")
 **/
/**
 * @SWG\Get(
 *     path="/api/hello",
 *     summary="Hello world test for API troubleshooting",
 *     @SWG\Response(response="200", description="Hello world example")
 * )
 **/
Route::middleware('api')->get('/hello', function (Request $request) {
    return 'hello world';
});

// This was the default file contents of this file, it has been disabled by PHP7-Laravel5-EnterpriseAuth
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/

/**
* @SWG\Get(
*     path="/api/assets",
*     tags={"Assets"},
*     summary="Get Assets",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="include",
*         in="query",
*         description="relationships to include (Comma seperated)",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="filter[serial]",
*         in="query",
*         description="serial of asset",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="filter[part_id]",
*         in="query",
*         description="part_id of asset",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="filter[vendor_id]",
*         in="query",
*         description="vendor_id of asset",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="filter[warranty_id]",
*         in="query",
*         description="warranty_id of asset",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="filter[location_id]",
*         in="query",
*         description="location_id of asset",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Get(
*     path="/api/assets/{id}",
*     tags={"Assets"},
*     summary="Get Asset by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Post(
*     path="/api/assets",
*     tags={"Assets"},
*     summary="Create a new Asset",
*     description="Create a new Asset",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="serial",
*         in="formData",
*         description="serial number",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="part_id",
*         in="formData",
*         description="id of part for this asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="vendor_id",
*         in="formData",
*         description="id of vendor this was purchased from",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="warranty_id",
*         in="formData",
*         description="id of warranty contract",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="location_id",
*         in="formData",
*         description="sysid of location this asset is at.",
*         required=true,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Put(
*     path="/api/assets/{id}",
*     tags={"Assets"},
*     summary="Update asset by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="serial",
*         in="formData",
*         description="serial of asset",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="part_id",
*         in="formData",
*         description="id of part",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="vendor_id",
*         in="formData",
*         description="id of vendor",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="warranty_id",
*         in="formData",
*         description="id of warranty",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="location_id",
*         in="formData",
*         description="sysid of snow location",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
/**
* @SWG\Delete(
*     path="/api/assets/{id}",
*     tags={"Assets"},
*     summary="Delete asset by ID",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
Route::apiResource('assets', 'AssetController');
/**
* @SWG\Get(
*     path="/api/parts",
*     tags={"Parts"},
*     summary="Get Parts",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Get(
*     path="/api/parts/{id}",
*     tags={"Parts"},
*     summary="Get Part by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Part",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Post(
*     path="/api/parts",
*     tags={"Parts"},
*     summary="Create a new Part",
*     description="Create a new Part",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="manufacturer_id",
*         in="formData",
*         description="Manufacturer ID",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="part_number",
*         in="formData",
*         description="part number of part",
*         required=true,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="list_price",
*         in="formData",
*         description="list price of part",
*         required=false,
*         type="number"
*     ),
*     @SWG\Parameter(
*         name="weight",
*         in="formData",
*         description="weight of part in lbs",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Put(
*     path="/api/parts/{id}",
*     tags={"Parts"},
*     summary="Update Part by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Part",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="manufacturer_id",
*         in="formData",
*         description="id of manufacturer",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="part_number",
*         in="formData",
*         description="part number",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="list_price",
*         in="formData",
*         description="list price of part",
*         required=false,
*         type="number"
*     ),
*     @SWG\Parameter(
*         name="weight",
*         in="formData",
*         description="weight of part",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
/**
* @SWG\Delete(
*     path="/api/parts/{id}",
*     tags={"Parts"},
*     summary="Delete Part by ID",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Part",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
Route::apiResource('parts', 'PartController');
/**
* @SWG\Get(
*     path="/api/partners",
*     tags={"Partners"},
*     summary="Get Partners",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Get(
*     path="/api/partners/{id}",
*     tags={"Partners"},
*     summary="Get Partner by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Partner",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Post(
*     path="/api/partners",
*     tags={"Partners"},
*     summary="Create a new Partner",
*     description="Create a new Partner",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="name",
*         in="formData",
*         description="Partner Name",
*         required=true,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="url",
*         in="formData",
*         description="Partner Url",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="discount",
*         in="formData",
*         description="Partner Discount in Percentage",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Partner Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Put(
*     path="/api/partners/{id}",
*     tags={"Partners"},
*     summary="Update Partner by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="name",
*         in="formData",
*         description="Partner name",
*         required=true,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
/**
* @SWG\Delete(
*     path="/api/partners/{id}",
*     tags={"Partners"},
*     summary="Delete Partner by ID",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Partner",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
Route::apiResource('partners', 'PartnerController');
/**
* @SWG\Get(
*     path="/api/contacts",
*     tags={"Contacts"},
*     summary="Get Contacts",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Get(
*     path="/api/contacts/{id}",
*     tags={"Contacts"},
*     summary="Get Contact by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Contact",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Post(
*     path="/api/contacts",
*     tags={"Contacts"},
*     summary="Create a new Contact",
*     description="Create a new Contact",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="name",
*         in="formData",
*         description="contact name",
*         required=true,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="email",
*         in="formData",
*         description="Contact Email",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="phone",
*         in="formData",
*         description="Contact Phone",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Contact Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="partner_id",
*         in="formData",
*         description="Contact Partner ID",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Put(
*     path="/api/contacts/{id}",
*     tags={"Contacts"},
*     summary="Update Contact by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Contact",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="name",
*         in="formData",
*         description="Contact Name",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="email",
*         in="formData",
*         description="Contact Email",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="phone",
*         in="formData",
*         description="Contact Phone",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Contact Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="partner_id",
*         in="formData",
*         description="Contact Partner ID",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
/**
* @SWG\Delete(
*     path="/api/contacts/{id}",
*     tags={"Contacts"},
*     summary="Delete Contact by ID",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Contact",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
Route::apiResource('contacts', 'ContactController');
/**
* @SWG\Get(
*     path="/api/contracts",
*     tags={"Contracts"},
*     summary="Get Contracts",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Get(
*     path="/api/contracts/{id}",
*     tags={"Contracts"},
*     summary="Get Contract by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Contract",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Post(
*     path="/api/contracts",
*     tags={"Contracts"},
*     summary="Create a new Contract",
*     description="Create a new Contract",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="cid",
*         in="formData",
*         description="Contract ID (from provider)",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="partner_id",
*         in="formData",
*         description="Partner ID",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Contract Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Put(
*     path="/api/contracts/{id}",
*     tags={"Contracts"},
*     summary="Update Contract by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="cid",
*         in="formData",
*         description="Contract ID (from provider)",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="partner_id",
*         in="formData",
*         description="Partner ID",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Contract Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
/**
* @SWG\Delete(
*     path="/api/contracts/{id}",
*     tags={"Contracts"},
*     summary="Delete Contract by ID",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Contract",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
Route::apiResource('contracts', 'ContractController');

/**
* @SWG\Get(
*     path="/api/locations",
*     tags={"Locations"},
*     summary="Get Locations",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Get(
*     path="/api/locations/{id}",
*     tags={"Locations"},
*     summary="Get Location by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Location",
*         required=true,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Post(
*     path="/api/locations",
*     tags={"Locations"},
*     summary="Create a new Location",
*     description="Create a new Location",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="cid",
*         in="formData",
*         description="Location ID (from provider)",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="partner_id",
*         in="formData",
*         description="Partner ID",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Contract Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
**/
/**
* @SWG\Put(
*     path="/api/locations/{id}",
*     tags={"Locations"},
*     summary="Update Contract by ID",
*     description="",
*     operationId="",
*     consumes={"application/x-www-form-urlencoded"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of asset",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="cid",
*         in="formData",
*         description="Contract ID (from provider)",
*         required=false,
*         type="string"
*     ),
*     @SWG\Parameter(
*         name="partner_id",
*         in="formData",
*         description="Partner ID",
*         required=false,
*         type="integer"
*     ),
*     @SWG\Parameter(
*         name="description",
*         in="formData",
*         description="Contract Description",
*         required=false,
*         type="string"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
/**
* @SWG\Delete(
*     path="/api/locations/{id}",
*     tags={"Locations"},
*     summary="Delete Contract by ID",
*     description="",
*     operationId="",
*     consumes={"application/json"},
*     produces={"application/json"},
*     @SWG\Parameter(
*         name="id",
*         in="path",
*         description="ID of Contract",
*         required=true,
*         type="integer"
*     ),
*     @SWG\Response(
*         response=200,
*         description="successful operation",
*     ),
*     @SWG\Response(
*         response="401",
*         description="Unauthorized user",
*     ),
*     security={
*         {"AzureAD": {}},
*     }
* )
*/
Route::apiResource('locations', 'LocationController');
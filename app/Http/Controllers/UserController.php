<?php

namespace App\Http\Controllers;
use App\Http\Resources\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\Post\UserInterface;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @SWG\GET(
     *   path="api/user",
     *   summary="All User",
     *   operationId="store",
     *   tags={"User"},
     *   security={
     *       {"ApiKeyAuth": {}}
     *   },
     *     @SWG\Parameter(
     *       name="first_name",
     *       in="query",
     *       required=true,
     *       type="string"
     *   ),
     *   @SWG\Response(response=200, description="successful operation"),
     *   @SWG\Response(response=406, description="not acceptable"),
     *   @SWG\Response(response=500, description="internal server error")
     * )
     *
     */


    protected $_userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {

        //$this->middleware('auth.role:Admin');
        $this->_userRepository = $userRepository;
    }


    public function index()
    {

        $result=$this->_userRepository->getUsers();
        return response()->json(UserResource::collection($result),Response::HTTP_OK,[],JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $check = '';
        try{
            $data = $request->only('name','phone_number','email','birthday','password','address','address_id','roles');
            //dd($data);
            $check =   $this->_userRepository->addUser($data) == true ? "OK" : "ER";
            $result = array(
                'status' => $check,
                'message'=> 'Insert Successfully',
                'data'=> ''
            );
            return response()->json($result,Response::HTTP_CREATED,[],JSON_NUMERIC_CHECK);
        }catch (Exception $e){
            $result = array(
                'status' => $check,
                'message'=> 'Insert Failed',
                'data'=> ''
            );
            return response()->json($result,Response::HTTP_BAD_REQUEST,[],JSON_NUMERIC_CHECK);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        $data_find = $this->_userRepository->find($id);
        if (is_null($data_find)){
            return response()->json("Record id not found",Response::HTTP_NOT_FOUND,[],JSON_NUMERIC_CHECK);
        }
        $result = array(
            'status' => 'OK',
            'message'=> 'Show Successfully',
            'data'=> $this->_userRepository->getUserById($id)
        );
        return response()->json($result,Response::HTTP_OK,[],JSON_NUMERIC_CHECK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $check = '';
        try {
            $data_find = $this->_userRepository->find($id);
            if (is_null($data_find)){
                return response()->json("Record is not found",Response::HTTP_NOT_FOUND,[],JSON_NUMERIC_CHECK);
            }
            $data = $request->only('name','phone_number','email','birthday','password','address','address_id','roles');
            $check =  $this->_userRepository->updateUser($id,$data) == true ? "OK" : "ER";
            $result = array(
                'status' => $check,
                'message'=> 'Update Successfully',
                'data'=> $data_find
            );
            return response()->json($result,Response::HTTP_OK,[],JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            $result = array(
                'status' => $check,
                'message'=> 'Update Failed',
                'data'=> ''
            );
            return response()->json($result,Response::HTTP_BAD_REQUEST,[],JSON_NUMERIC_CHECK);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check = '';
        try {
            $data_find = $this->_userRepository->find($id);
            if (is_null($data_find)){
                return response()->json("Record is not found",Response::HTTP_NOT_FOUND,[],JSON_NUMERIC_CHECK);
            }
            $check = $this->_userRepository->deleteUser($id) == true ? "OK" : "ER";
            $result = array(
                'status' => $check,
                'message'=> 'Delete Successfully',
                'data'=> ''
            );
            dd($result);
            return response()->json($result,Response::HTTP_NO_CONTENT,[],JSON_NUMERIC_CHECK);
        } catch (Exception $e) {
            $result = array(
                'status' => $check,
                'message'=> 'Delete Failed',
                'data'=> ''
            );
            return response()->json($result,Response::HTTP_BAD_REQUEST,[],JSON_NUMERIC_CHECK);
        }
    }
}

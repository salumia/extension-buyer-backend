<?php

namespace App\Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User as User;
use App\Modules\Country\Models\Country as Country;
use App\Modules\Product\Models\Product as Product;
use App\Modules\Product\Models\Product_type as ProductType;
use App\Modules\State\Models\State as State;
use App\Modules\City\Models\City as City;
use App\Modules\Admin\Http\Controllers\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['email'])){
          $users = User::Where('email', 'like', '%' . $_GET['email'] . '%')->orderBy('id', 'DESC')->get();
        }else{
            $users = User::all();
        } 
        $country=Country::all();
        return view('Admin::user.users',compact('users','country'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries=Country::orderBy('name','ASC')->get();
        return view('Admin::user.add_user',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=$request->all();
        $validator = Validator::make($request->all(), [ 
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'address_line' => 'required',
            'zip_code' => 'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 200);            
        }
        $status=1;
        $Password=12345;
        $user= new User;
        $user->name=$request->firstName;
        $user->last_name=$request->lastName;
        $user->email=$request->email;
        $user->phone_no=$request->phone_no;
        $user->country_id=$request->country_id;
        $user->state_id=$request->state_id;
        $user->city_id=$request->city_id;
        $user->password=Hash::make($Password);
        $user->status=$status;
        $user->address_line=$request->address_line;
        $user->zip_code=$request->zip_code;
        $user->save();
        if(!empty($request->lastName)){
            $to=$request->lastName;
            $subject = 'Extension buyer Account Details';
            $headers = "From: extensionbuyer@gmail.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $message ="<p><strong>Hey " .$request->name . "</strong></p>";
            $message .="<p>You account create Successfully on ExtensioinBuyer and your Email:".$request->email." and Password :".$Password." Please login your account</p>";
            $message ="<p><strong>Thankyou</strong></p>";
            mail($to, $subject, $message, $headers);
        }
        return redirect('/admin/user')->with('success','User Saved Successfully.');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = array();
        $detail=User::find($id);
        $user['state']='';
        $user['city']='';
        $user['country']='';
        $user['detail']=$detail;
        $country=Country::select('name')->where('id','=',$detail->country_id)->first();
        if($country){
          $user['country']=$country['name'];
        }
        $state=State::select('name')->where('id','=',$detail->state_id)->first();
        if($state){
          $user['state']=$state['name'];
        }
        $city=City::select('name')->where('id','=',$detail->city_id)->first();
        if($city){
          $user['city']=$city['name'];
        }
        $name=$user['detail']->image_path;
        $url =  url('/').'/images/upload/'.$name;
        $user['detail']->image_path =$url;
        

    
        //dd($user);
            $extensions = array();
            $products=Product::where('user_id', '=', $id)->get();
            $products1 = (array) $products->toArray();
              foreach($products1 as $key => $product){
                $product = (array) $product;
                $productType=$product['product_type'];
                $product_id=$product['id'];
                
                $product_type = ProductType::select('type')->where('id','=',$productType)->first();
                $product['type']=$product_type->type;
                
                $received_offer=DB::table('products')->where('user_id', '=', $id)->count();
                
                $total_offer=DB::table('offers')->where('product_id', '=', $product_id)->count();
                $total_amount = 0;
                if($total_offer != 0){
                    $total_amount=DB::table('offers')->where('product_id', '=', $product_id)->sum('offered_amount');
                    $total_amount = $total_amount/$total_offer;
                    
                }
                 $product['received_offer'] = $received_offer;
                 $product['avg_offers'] = $total_amount;
                
                $image=DB::table('product_images')->select( 'id','image_path','type')->where('product_id', '=', $product_id)->get();
                foreach($image as $img){
                    $name=$img->image_path;
                    $url =  url('/').'/laravelcode/public/images/productmedia/'.$name;
                    $img->image_path =$url;
                }
                $images = (array)  $image->toArray();
                $product['images'] = $images;
                $extensions[] = $product;
            }

        //dd($user);
        return view('Admin::user.view_user',compact('user','extensions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::find($id);
        $countries=Country::orderBy('name','ASC')->get();
        $states=State::where('country_id',$user->country_id)->orderBy('name','ASC')->get();
        $cities=City::where('state_id',$user->state_id)->orderBy('name','ASC')->get();
        return view('Admin::user.edit_user',compact('user','countries','cities','states'));
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
        $user=User::find($id);
        $data=$request->all();
        $validator = Validator::make($request->all(), [ 
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required',
            'phone_no' => 'required|max:10',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'address_line' => 'required',
            'zip_code' => 'required',
            'status'=>'required',
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 200);            
        }
        $user->name=$request->firstName;
        $user->last_name=$request->lastName;
        $user->email=$request->email;
        $user->phone_no=$request->phone_no;
        $user->country_id=$request->country_id;
        $user->state_id=$request->state_id;
        $user->city_id=$request->city_id;
        $user->address_line=$request->address_line;
        $user->zip_code=$request->zip_code;
        $user->status=$request->status;
        $user->save();
        return redirect('/admin/user')->with('success','User Update Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user= User::find($id);
        $products=Product::where('user_id','=',$id)->get();
        foreach($products as $product){
          $product->Delete();
        }
        $user->Delete();
        return redirect('/admin/user')->with('success','User Delete Successfully.');
    }
}

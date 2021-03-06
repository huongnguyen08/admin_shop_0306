<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TypeProducts;
use ReCaptcha\ReCaptcha;
use Validator;
use App\User;
use Hash;
use Auth;
use App\Products;
use Maatwebsite\Excel\Facades\Excel;


class AdminController extends Controller
{
	public function captchaCheck($response)
    {

        //$response = $req->input('g-recaptcha-response');

        $remoteip = $_SERVER['REMOTE_ADDR']; //192.168.0.0
        $secret   = env('RE_CAP_SECRET'); //6LcuZzIUAAAAACEYzMomFwEjM_arJ3S1uHcpVMOM

        $recaptcha = new ReCaptcha($secret);
        $resp = $recaptcha->verify($response, $remoteip);
        if ($resp->isSuccess()) {
            return 1;
        } else {
            return 0;
        }

    }

	public function getRegister(){
		return view('register');
	}

	public function postRegister(Request $req){
		$data = $req->all();

		$response = $req->input('g-recaptcha-response');

		$data['captcha'] = $this->captchaCheck($response);

		$validator = Validator::make($data,[
			'fullname' => 'required|min:10|max:50',
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6|max:20',
			're_password' => 'required|same:password',
			'g-recaptcha-response'  => 'required',
            'captcha'               => 'required|min:1'
		],
		[
			'fullname.required' => 'Họ tên không rỗng',
			'fullname.min' => 'Tên ít nhất 10 kí tự',
			'email.unique' => 'Email đã có người sử dụng',
			're_password.same' =>'Mật khẩu không giống nhau',
			'g-recaptcha-response.required' => 'Vui lòng check ReCaptcha',
			'captcha.required' => 'Vui lòng check captcha',
            'captcha.min' => 'Captcha không chính xác, vui lòng thử lại.'
		]);
		if ($validator->fails()) {
            return redirect()->route('register')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user = new User;
        $user->full_name = $req->fullname;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->save();
        return redirect()->route('home');
		
	}

    public function getLogin(){
        return view('login');
    }


    public function postLogin(Request $req){
        $data = $req->all();
        $validator = Validator::make($data,[
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
        ],
        [
            'email.email' => 'Email không đúng định dạng',
            //
            //
            //
            //
            //
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                        ->withErrors($validator)
                        ->withInput();
        }

        $infor_user =   [ 
                            'email'=>$req->email,
                            'password'=>$req->password
                        ];
        if(Auth::attempt($infor_user,$req->has('remember'))){
            
            return redirect()->route('home');
        }
        else{
            return redirect()->back()->with('loi','Đăng nhập ko thành công');
        }


    }

    public function getLogout(){
        Auth::logout();
        return redirect()->route('login');
    }


    public function getIndex(){

    	$type = TypeProducts::all();
    	return view('pages.index',compact('type','active'));
    	//return view('pages.index',['type'=>$type]);
    }

    public function getListUser(){
        $users = User::all();
        return view('pages.list-user',compact('users','active'));
    }

    public function getEditUser(Request $req){
        $user = User::where('id',$req->id)->first();
        if($user){
            if($req->status == 0){
                //active cho user
                $user->active = 1;
                $user->save();
            }
            elseif($req->status == 1){
                //set admin
                $user->role = 1;
                $user->save();
            }
            else{
                return redirect()->back()->with([
                    'message'=>'Liên kết bạn nhập vào không hợp lệ',
                    'flag' => 'danger'
                ]);
            }
            return redirect()->back()->with([
                'message'=>'Update thành công',
                'flag' => 'success'
            ]);
        }
        else{
            return redirect()->back()->with([
                'message'=>'không tồn tại user này',
                'flag' => 'danger'
            ]);
        }
    }


    public function getListProductByIdType(Request $req){
        $type = TypeProducts::select('name')->where('id',$req->id)->first();
        //dd($type); die;
        if($type){
            $products = Products::where('id_type',$req->id)->paginate(5);
            if($products){
                return view('pages.list-product',compact('products','type'));
            }
            return redirect()->route('home')->with('error','Không thể xử lý yêu cầu.');
        }
        else{
            return redirect()->route('home')->with('error','Không thể xử lý yêu cầu.');
        }
        
    }

    public function getEditTypeByIdType(Request $req){
        $type = TypeProducts::where('id',$req->id)->first();
        if($type){
            return view('pages.edit-type',compact('type'));
        }
        else{
            return redirect()->route('home')->with('error','Không thể xử lý yêu cầu.');
        }
    }

    public function postEditTypeByIdType(Request $req){
        //validate


        $type = TypeProducts::where('id',$req->id)->first();
        if($type){
            $type->name = $req->name;
            $type->description = $req->desc;
            if($req->hasFile('hinh')){
                $hinh = $req->file('hinh');

                $nameFile = time().'-'.$hinh->getClientOriginalName();
                $hinh->move('admin_theme/img/product',$nameFile);

                //lưu tên của hình vào db
                $type->image = $nameFile;
            }
            $type->save();
            return redirect()->route('home')->with('success','Lưu thành công.');
        }
        else{
            return redirect()->route('home')->with('error','Không thể xử lý yêu cầu.');
        }
    }

    public function getAddTypeProduct(){
        return view('pages.add_type');
    }

    public function postAddTypeProduct(Request $req){
        //validate

        $type = new TypeProducts;

        $type->name = $req->name;
        $type->description = $req->desc;

        if($req->hasFile('hinh')){
            $hinh = $req->file('hinh');

            $nameFile = time().'-'.$hinh->getClientOriginalName();
            $hinh->move('admin_theme/img/product',$nameFile);

            //lưu tên của hình vào db
            $type->image = $nameFile;
        }
        $type->save();
        return redirect()->route('home')->with('success','Lưu thành công.');
        
        
    }

    public function postDeleteTypeProduct(Request $req){
        $type = TypeProducts::where('id',$req->id)->first();
        $products = Products::where('id_type',$req->id)->get();

        if($type && count($products)<=0){

            $type->delete();
            echo 'done';
        }
        else{
            echo 'error';
        }
    }

    public function getExportProducts(){
        $products = Products::all();

        return Excel::create('namefile', function($excel) use ($products) {
            
            $excel->sheet('nameSheet', function($sheet) use ($products)
            {
                $sheet->fromArray($products, null, 'A1', false, false);

                $headings = array('ID Sản phẩm', 'Tên sản phẩm', 'Mã loại' ,'Mô tả', 'Đơn giá', 'Đơn giá KM', 'Hình' , 'Đơn vị tính', 'Ngày tạo', 'Ngày sửa');
                $sheet->prependRow(1, $headings);
            });
        })->download('xlsx');
    }

    public function getExportProductsMultisheet(){
        $types = TypeProducts::with('Products')->get();

        return Excel::create('namefile', function($excel) use ($types) {
            
            foreach ($types as $loaiSP) {

                $excel->sheet("$loaiSP->name", function($sheet) use ($loaiSP)
                {
                    $sheet->fromArray($loaiSP->Products, null, 'A1', false, false);


                    $headings = array('ID Sản phẩm', 'Tên sản phẩm', 'Mã loại' ,'Mô tả', 'Đơn giá', 'Đơn giá KM', 'Hình' , 'Đơn vị tính', 'Ngày tạo', 'Ngày sửa');
                    $sheet->cells("A1:J1", function($cells) {
                        $cells->setBackground('#8af2ee');
                    });
                    $sheet->prependRow(1, $headings);
                });
            }
        })->download('xlsx');
    }

    public function getViewImport(){
        return view('pages.import');
    }

    public function import(Request $req){

        $excel = [];
        Excel::load($req->file('file'), function ($reader) use (&$excel) {
            $objExcel = $reader->getExcel();
            $sheet = $objExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            //  Loop through each row of the worksheet in turn
            //lặp từ dòng 3 trong file excel
            for ($row = 3; $row <= $highestRow; $row++)
            {
                //  Read a row of data into an array
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
                    NULL, TRUE, FALSE);
                $excel[] = $rowData[0];
            }

        }); 
        //dd($excel);

        foreach ($excel as $data) {
            $product = new Products;
            $product->name = $data[1];
            $product->id_type = $data[2];
            $product->description = $data[3];
            $product->unit_price = $data[4];
            $product->promotion_price = $data[5];
            $product->image = $data[6];
            $product->unit = $data[7];
            $product->created_at = $data[8];
            $product->updated_at = $data[9];
            $product->save();

        }
        echo 'thêm thành công';
        
    }
}

<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Manager extends Controller
{
    public function index()
    {}
	public function ManagerIndex()
	{
	    session_start();
        if (isset($_SESSION['adminID']))
        {
            $this->assign('name',$_SESSION['name']);
        }
		return $this->fetch();
	}
	public function password()
    {
        session_start();
        if(!isset($_SESSION['adminID'])){
            $url = str_replace(".html", "", url("Index/index"));
            $url = str_replace(".php", "", $url);
            $url = str_replace("/index", "", $url);
            $this->error("You need to log in first!", $url);
        }
        if(isset($_SESSION['name']))
        {
            $this->assign('name',$_SESSION['name']);
        }
/*
        if(isset($_SESSION['adminID']))
        {
            $this->assign('adminID',$_SESSION['adminID']);
      }
*/
        return $this->fetch();
    }
    public function Detailinfo()
    {
        session_start();
        if( !isset($_SESSION['adminID']))
        {
            $url = str_replace(".html", "", url("Index/index"));
            $url = str_replace(".php", "", $url);
            $url = str_replace("/index", "", $url);
            $this->error("You need to log in first!", $url);
        }
        if(isset($_SESSION['name']))
        {
            $this->assign('name',$_SESSION['name']);
        }
        if(isset($_SESSION['username']))
        {
            $this->assign('username',$_SESSION['username']);
        }
        if(isset($_SESSION['adminID']))
        {
            $this->assign('adminID', $_SESSION['adminID']);
        }
        $this->assign('adminID',$_SESSION['adminID']);
        $adminID=$_SESSION['adminID'];
        $condition=array('adminID'=>$adminID);
        $admin=db('admin')->where($condition)->find();
        $this->assign('username',$admin['username']);
        $this->assign('fname',$admin['first_name']);
        $this->assign('lname',$admin['last_name']);
        $this->assign('email',$admin['email']);

        return $this->fetch();
    }
    public function newAdmin()
    {
        session_start();
        if(isset($_SESSION['name']))
        {
            $this->assign('name',$_SESSION['name']);
        }
        if(isset($_SESSION['username']))
        {
            $this->assign('username',$_SESSION['username']);
        }
        if(isset($_SESSION['adminID']))
        {
            $this->assign('adminID',$_SESSION['adminID']);
        }
        return $this->fetch();
    }
    public function newProduct()
    {
        session_start();
        if(isset($_SESSION['name']))
        {
            $this->assign('name',$_SESSION['name']);
        }
        if(isset($_SESSION['username']))
        {
            $this->assign('username',$_SESSION['username']);
        }
        if(isset($_SESSION['adminID']))
        {
            $this->assign('adminID',$_SESSION['adminID']);
        }

        $cpus = Db::table("hardwares")->where('hardware_categoryID',1)->select();
        $this->assign('cpu',$cpus);
        $gpus = Db::table("hardwares")->where('hardware_categoryID',2)->select();
        $this->assign('gpu',$gpus);
        $RAM_sizes = Db::table("hardwares")->where('hardware_categoryID',3)->select();
        $this->assign('RAM_size',$RAM_sizes);
        $screen_sizes = Db::table("hardwares")->where('hardware_categoryID',4)->select();
        $this->assign('screen_sizes',$screen_sizes);
        $hard_disk_desc = Db::table("hardwares")->where('hardware_categoryID',5)->select();
        $this->assign('Hard_disk_Description',$hard_disk_desc);
        $hard_drive_sizes = Db::table("hardwares")->where('hardware_categoryID',6)->select();
        $this->assign('Hard_Drive_Size',$hard_drive_sizes);
        $os = Db::table("hardwares")->where('hardware_categoryID',7)->select();
        $this->assign('os',$os);
        $screen_resolutions = Db::table("hardwares")->where('hardware_categoryID',8)->select();
        $this->assign('screen_resolutions',$screen_resolutions);

        return $this->fetch();
    }
    public function  CheckOrder()
    {
        session_start();
        if(isset($_SESSION['username']))
        {
            $this->assign('username',$_SESSION['username']);
            $this->assign('name',$_SESSION['name']);
        }
        if (isset($_SESSION['adminID']))
        {
            $this->assign('adminID',$_SESSION['adminID']);
        }

        $orders=Db('products')->alias('p')->join('orders o','o.productID=p.productID')->select();
        $this->assign('orders',$orders);
        $productIDs=array();
        foreach ($orders as $pro)
        {
            $productIDs[]=$pro['productID'];
        }
        $products=Db("products")->where('productID','=',$productIDs);
        $this->assign('products',$products);
        return $this->fetch();
    }
    public function CheckProduct()
    {
        session_start();
        if(isset($_SESSION['username']))
        {
            $this->assign('username',$_SESSION['username']);
            $this->assign('name',$_SESSION['name']);
        }
        if (isset($_SESSION['adminID']))
        {
            $this->assign('adminID',$_SESSION['adminID']);
        }

        $products=Db('products')->order(['sales_volume'=>'desc'])->select();
        $this->assign('products',$products);
        return $this->fetch();
    }
    	public function Customer()
    	{
    	    session_start();
            if(isset($_SESSION['name']))
            {
                $this->assign('name',$_SESSION['name']);
            }
            if(isset($_SESSION['username']))
            {
                $this->assign('username',$_SESSION['username']);
            }
            if(isset($_SESSION['adminID']))
            {
                $this->assign('adminID',$_SESSION['adminID']);
            }

            //home customer register
            $result = db("Homec_count_r")->select();
            $Homec_r=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $Homec_r[] = $a['count'];
                }
            }
            //business customer register
            $result = db("Businessc_count_r")->select();
            $Businessc_r=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $Businessc_r[] = $a['count'];
                }
            }

            //home customer made order
            $result = db("Homec_count")->select();
            $Homec=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $Homec[] = $a['count'];
                }
                $this->assign('total_Homec',$Homec[0]);
            }
            //business customer made order
            $result = db("Businessc_count")->select();
            $Businessc=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $Businessc[] = $a['count'];
                }
                $this->assign('total_Businessc',$Businessc[0]);
            }
            $ratio_r=$Homec_r[0]/$Businessc_r[0];
            $ratio=$Homec[0]/$Businessc[0];
            $this->assign('ratio_r',$ratio_r);
            $this->assign('ratio',$ratio);

            $result=db("Businessc_dimension")->select();
            $this->assign('Bdimension',$result);

            $result=db('Homec_dimension')->select();
            $this->assign('Hdimension',$result);

    		return $this->fetch();
    	}
        public function Brands()
        {
            session_start();
            if(isset($_SESSION['name']))
            {
                $this->assign('name',$_SESSION['name']);
            }
            if(isset($_SESSION['username']))
            {
                $this->assign('username',$_SESSION['username']);
            }
            if(isset($_SESSION['adminID']))
            {
                $this->assign('adminID',$_SESSION['adminID']);
            }
            //Total sales
            $array=DB('Total_sales')->select();
            $total_sales=array();
            foreach ($array as $a)
            {
                $total_sales[]=$a['sales'];
            }
            if($total_sales)
            {
                $this->assign('total_sales',$total_sales[0]);
            }
            else
            {
                $this->error("Something wrong happen");
            }
            // Dell
            $result = db("Brand_sales")->where('brand',"Dell")->select();
            $dell_sales=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $dell_sales[]=$a['sales'];
                }
                $this->assign('dell_sales',$dell_sales[0]);
            }

            //Apple
            $result = db("Brand_sales")->where('brand',"Apple")->select();
            $apple_sales=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $apple_sales[]=$a['sales'];
                }
                $this->assign('apple_sales',$apple_sales[0]);
            }

            //Acer
            $result = db("Brand_sales")->where('brand',"Acer")->select();
            $acer_sales=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $acer_sales[]=$a['sales'];
                }
                $this->assign('acer_sales',$acer_sales[0]);
            }
            //ASUS
            $result = db("Brand_sales")->where('brand',"ASUS")->select();
            $asus_sales=array();
            if($result)
            {
                foreach ($result as $a)
                {
                    $asus_sales[]=$a['sales'];
                }
                $this->assign('asus_sales',$asus_sales[0]);
            }
            //
            $result = db("Brand_sales")->select();
            $this->assign('brands',$result);


            return $this->fetch();
        }
        public function Sales()
        {
            session_start();
            if(isset($_SESSION['name']))
            {
                $this->assign('name',$_SESSION['name']);
            }
            if(isset($_SESSION['username']))
            {
                $this->assign('username',$_SESSION['username']);
            }
            if(isset($_SESSION['adminID']))
            {
                $this->assign('adminID',$_SESSION['adminID']);
            }
            return $this->fetch();
        }
        public function BvsP()
        {
            session_start();
            if(isset($_SESSION['name']))
            {
                $this->assign('name',$_SESSION['name']);
            }
            if(isset($_SESSION['username']))
            {
                $this->assign('username',$_SESSION['username']);
            }
            if(isset($_SESSION['adminID']))
            {
                $this->assign('adminID',$_SESSION['adminID']);
            }
            $result=db('Product_dimension')->select();
            $this->assign('Pdimension',$result);

            if(isset($_POST['Pname']))
            {
                $Pname=$_POST['Pname'];
            }
            else
            {
                $Pname="MacBook Air";
            }
            $result=db('Pname_bcategory_sales')->where('Pname',$Pname)->order(['sales'=>'desc'])->select();
            $this->assign('Pname_Bcategory_sales',$result);
            return $this->fetch();
        }
        public function demand()
        {
            session_start();
            if(isset($_SESSION['name']))
            {
                $this->assign('name',$_SESSION['name']);
            }
            if(isset($_SESSION['username']))
            {
                $this->assign('username',$_SESSION['username']);
            }
            if(isset($_SESSION['adminID']))
            {
                $this->assign('adminID',$_SESSION['adminID']);
            }
            return $this->fetch();
        }
}
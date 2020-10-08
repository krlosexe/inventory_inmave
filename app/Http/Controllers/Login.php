<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Modulos;
use App\funciones;
use App\AuthUsers;
use App\AuthUsersApp;
use App\LogsSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
    public function Auth(request $request)
    {	
    	$messages = [
		    'required' => 'El Campo :attribute es requirdo.',
		];


    	$validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ], $messages);


        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(400);
        }else{

            $users = User::join("auditoria", "auditoria.cod_reg", "=", "users.id")
                        ->join("datos_personales", "datos_personales.id_usuario", "users.id")
                         ->where("email", $request["email"])
                         ->where("password", md5($request["password"]))
                         ->where("auditoria.tabla", "users")
                         ->where("auditoria.status", "!=", "0")
	    				 ->get();

	    	if (sizeof($users) > 0) {
	    		$token = bin2hex(random_bytes(64));


	    		$token_user  = AuthUsers::where("id_user", $users[0]->id)->get();

	    		foreach ($token_user as $key => $value) {
					$value->delete();
	    		}

	    		$AuthUsers          = new AuthUsers;
		        $AuthUsers->id_user = $users[0]->id;
		        $AuthUsers->token   = $token;
                $AuthUsers->save();
                


                LogsSession::create(["id_user" => $users[0]->id, "date_login" => date("Y-m-d G:i:s")]);


	    		$data = array('user_id'  => $users[0]->id,
                              'email'    => $users[0]->email,
                              'nombres'  => $users[0]->nombres." ".$users[0]->apellido_p,
	    					  'token'    => $token,
	    					  'mensagge' => "Ha iniciado sesion exitosamente"
	    		);

	    		return response()->json($data)->setStatusCode(200);
	    	}else{
	    		return response()->json("Usuario o contrasena invalida")->setStatusCode(400);
	    	}
        }


    }









    public function AuthApp(request $request)
    {	

        if($request["email"] == "" || $request["password"] == ""){

            return response()->json("El Email y Contraseña son Requeridos")->setStatusCode(400);

        }

        $users = User::join("datos_personales", "datos_personales.id_usuario", "users.id")
                         ->where("users.email", $request["email"])
                         ->where("users.password", md5($request["password"]))
                        // ->where("auditoria.tabla", "users")
                       //  ->where("auditoria.status", "!=", "0")
	    				 ->get();

        if (sizeof($users) > 0) {
            
            $token = bin2hex(random_bytes(64));


            $token_user  = AuthUsersApp::where("id_user", $users[0]->id)->get();

            foreach ($token_user as $key => $value) {
                $value->delete();
            }

            $AuthUsers                       = new AuthUsersApp;
            $AuthUsers->id_user              = $users[0]->id;
            $AuthUsers->token                = $token;
            $AuthUsers->token_notifications  = $request["fcmToken"];
            $AuthUsers->save();
            
            $id_line = null;
            if($users[0]->id_rol == 6 || $users[0]->id_rol == 9){
                $name_rol = "Asesor";


                $line = DB::table("users_line_business")
                            ->where("users_line_business.id_user", $users[0]->id)
                            ->first();

                $id_line = $line->id_line;


            }

           
            if($users[0]->id_rol == 17){
                $name_rol = "Afiliado";


                $line = DB::table("users")
                            ->select("clientes.id_line")
                            ->join("clientes", "clientes.id_cliente", "=", "users.id_client")
                            ->where("users.id", $users[0]->id)
                            ->first();

                $id_line = $line->id_line;
            }

            if($users[0]->id_rol == 19){
                $name_rol = "Referido";


                $line = DB::table("users")
                            ->select("clientes.id_line")
                            ->join("clientes", "clientes.id_cliente", "=", "users.id_client")
                            ->where("users.id", $users[0]->id)
                            ->first();

                $id_line = $line->id_line;

                
            }


            if($users[0]->id_rol == 20){
                $name_rol = "Doctor";
            }


            $data = array('user_id'   => $users[0]->id,
                          'email'     => $users[0]->email,
                          'nombres'   => $users[0]->nombres." ".$users[0]->apellido_p,
                          'avatar'    => "http://pdtclientsolutions.com/crm-public/img/usuarios/profile/".$users[0]->img_profile,
                          'token'     => $token,
                          'code'      => "DOXQ",
                          'line'      => $id_line,
                          'token_chat' => $users[0]->token_chat,
                          'mensagge'  => "Ha iniciado sesion exitosamente",
                          "type_user" => $name_rol
                );
                
            if($users[0]->id_rol == 19){
                $data["id_client"] = $users[0]->id_client;
            }



            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("Usuario o contrasena invalida")->setStatusCode(400);
	    }
    }







    public function AuthDoc(request $request)
    {	

        if($request["email"] == "" || $request["password"] == ""){

            return response()->json("El Email y Contraseña son Requeridos")->setStatusCode(400);

        }

        $users = User::join("datos_personales", "datos_personales.id_usuario", "users.id")
                         ->where("email", $request["email"])
                         ->where("id_rol", "!=", 17)
                         ->where("id_rol", "!=", 19)
                         ->where("password", md5($request["password"]))
                        // ->where("auditoria.tabla", "users")
                       //  ->where("auditoria.status", "!=", "0")
	    				 ->get();

        if (sizeof($users) > 0) {
            
            $token = bin2hex(random_bytes(64));


            $token_user  = AuthUsersApp::where("id_user", $users[0]->id)->get();

            foreach ($token_user as $key => $value) {
                $value->delete();
            }

            $AuthUsers                       = new AuthUsersApp;
            $AuthUsers->id_user              = $users[0]->id;
            $AuthUsers->token                = $token;
            $AuthUsers->token_notifications  = $request["fcmToken"];
            $AuthUsers->save();
            
            if($users[0]->id_rol == 6 || $users[0]->id_rol == 9){
                $name_rol = "Asesor";
            }

            if($users[0]->id_rol == 20){
                $name_rol = "Doctor";
            }


            if($users[0]->id_rol == 19){
                $name_rol = "Referido";
            }


            $data = array('user_id'    => $users[0]->id,
                          'email'      => $users[0]->email,
                          'nombres'    => $users[0]->nombres." ".$users[0]->apellido_p,
                          'avatar'     => "http://pdtclientsolutions.com/crm-public/img/usuarios/profile/".$users[0]->img_profile,
                          'token'      => $token,
                          'sync_token' => $users[0]->sync_token,
                          'mensagge'   => "Ha iniciado sesion exitosamente",
                          "type_user"  => $name_rol
	    		);

            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("Usuario o contrasena invalida")->setStatusCode(400);
	    }
    }



    public function authValoration(request $request)
    {	

        if($request["email"] == "" || $request["password"] == ""){

            return response()->json("El Email y Contraseña son Requeridos")->setStatusCode(400);

        }

        $users = User::join("datos_personales", "datos_personales.id_usuario", "users.id")
                         ->where("email", $request["email"])
                         ->where("password", md5($request["password"]))
	    				 ->get();

        if (sizeof($users) > 0) {
            
            $token = bin2hex(random_bytes(64));


            $token_user  = AuthUsersApp::where("id_user", $users[0]->id)->get();

            foreach ($token_user as $key => $value) {
                $value->delete();
            }

            $AuthUsers                       = new AuthUsersApp;
            $AuthUsers->id_user              = $users[0]->id;
            $AuthUsers->token                = $token;
            $AuthUsers->token_notifications  = $request["fcmToken"];
            $AuthUsers->save();
            
            if($users[0]->id_rol == 6 || $users[0]->id_rol == 9){
                $name_rol = "Asesor";
            }

            if($users[0]->id_rol == 17){
                $name_rol = "Referido";
            }

            if($users[0]->id_rol == 19){
                $name_rol = "Referido";
            }


            if($users[0]->id_rol == 20){
                $name_rol = "Doctor";
            }


            $data = array('user_id'    => $users[0]->id,
                          'email'      => $users[0]->email,
                          'nombres'    => $users[0]->nombres." ".$users[0]->apellido_p,
                          'avatar'     => "http://pdtclientsolutions.com/crm-public/img/usuarios/profile/".$users[0]->img_profile,
                          'token'      => $token,
                          'sync_token' => $users[0]->sync_token,
                          'mensagge'   => "Ha iniciado sesion exitosamente",
                          "type_user"  => $name_rol
	    		);

            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("Usuario o contrasena invalida")->setStatusCode(400);
	    }
    }








    public function VerifyToken(request $request)
    {

    	$AuthUsers = AuthUsers::where("token", $request["token"])
                                ->where("id_user", $request["user_id"])
                                ->get();
        
        if (sizeof($AuthUsers) > 0) {

            $modulos = Modulos::join("auditoria", "auditoria.cod_reg", "=", "modulos.id_modulo")
                                ->where("auditoria.tabla", "modulos")
                                ->where("auditoria.status", 1)
                                ->orderBy("posicion", "asc")
                                ->get();


            $funciones = User::select("users.*", "rol_operaciones.*", "funciones.*")
                                ->join("roles", "roles.id_rol", "=", "users.id_rol")
                                ->join("rol_operaciones", "rol_operaciones.id_rol", "=", "roles.id_rol")
                                ->join("funciones", "funciones.id_funciones", "=", "rol_operaciones.id_funciones")
                                ->join("auditoria", "auditoria.cod_reg", "=", "roles.id_rol")
                                
                                ->where("id", $request["user_id"])
                                ->where("auditoria.tabla", "roles")
                                ->where("funciones.visibilidad", 1)

                                ->where(function($q) {
                                  $q->orWhere('rol_operaciones.general', 1)
                                    ->orWhere('rol_operaciones.detallada', 1)
                                    ->orWhere('rol_operaciones.registrar', 1)
                                    ->orWhere('rol_operaciones.actualizar', 1)
                                    ->orWhere('rol_operaciones.eliminar', 1);
                                })

                                ->orderBy("funciones.posicion", "asc")
                                ->get();


                $data_user = User::select("users.*", "roles.nombre_rol", "datos_personales.*")
                                      ->join('datos_personales', 'datos_personales.id_usuario', '=', 'users.id')
                                      ->join("roles", "roles.id_rol", "=", "users.id_rol")
                                      ->where("id", $request["user_id"])
                                      ->get();


            $modulos_disponibles = $this->control($modulos, $funciones);

        	$data = array('mensagge'            => "ok", 
                          'data'                => $AuthUsers[0],
                          'data_user'           => $data_user[0],
                          'modulos_disponibles' => $modulos_disponibles,
                          'funciones'           => $funciones
                    );

    		return response()->json($data)->setStatusCode(200);

        }else{
        	$data = array('mensagge' => "error");
    		return response()->json($data)->setStatusCode(500);
        }                                
    }



    public function control($modulos, $funciones)
    {
        $data = array();
        foreach ($modulos as $modulo) {
            foreach ($funciones as $vista) {
                if($modulo->id_modulo == $vista->id_modulo){
                    $data["modulo_user"][] = $modulo->id_modulo;
                }
            }
        }

        if (isset($data)) {
            $ids = array_unique($data['modulo_user']);
            foreach ($ids as $value) {

                $data['modulos_enconctrados'][] = $this->modulosbyid($value);
            } 

            $oneDim = array();
            foreach($data['modulos_enconctrados'] as $i) {
              $oneDim[] = $i[0];
            }


             return $data['modulos_vistas'] = $oneDim;
        }

    }


    public function modulosbyid($id)
    {
        $modulos = Modulos::join("auditoria", "auditoria.cod_reg", "=", "modulos.id_modulo")
                                ->where("auditoria.tabla", "modulos")
                                ->where("auditoria.status", 1)
                                ->where("modulos.id_modulo", $id)
                                ->orderBy("posicion", "asc")
                                ->get();
        return $modulos;
    }




    public function VerifyPermiso(Request $request)
    {

        $users = User::where("id", $request["user_id"])
                       ->join("roles", "roles.id_rol", "=", "users.id_rol")
                       ->join("rol_operaciones", "rol_operaciones.id_rol", "=", "roles.id_rol")
                       ->join("funciones", "funciones.id_funciones", "=", "rol_operaciones.id_funciones")
                            ->where("funciones.route", $request["route"])
                        ->get();
                         

        return response()->json($users[0])->setStatusCode(200);
    }

    public function Logout($user_id)
    {

    	$token_user = AuthUsers::where("id_user", $user_id)->get();

		foreach ($token_user as $key => $value) {
			$value->delete();
        }
        

        LogsSession::create(["id_user" => $user_id, "date_logout" => date("Y-m-d G:i:s")]);




		return redirect(url('/'));

    }

}

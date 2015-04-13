<?php
class usuarioControlador extends Cf_Controlador
{
    private $_ayuda;
    private $_seg;
    private $_sesion;    
    public function __construct() {
        parent::__construct();
               
        // cargamos la clase ayudantes para usar sus metodos de ayuda
        $this->cargaAyudante('Cf_PHPAyuda');
        $this->_ayuda= new Cf_PHPAyuda;
        $this->cargaAyudante('Cf_PHPSeguridad');
        $this->_seg= new Cf_PHPSeguridad;
        $this->_sesion=new Cf_Sesion();        
    }    
    
    public function index(){        
        $this->_vista->titulo = 'CalimaFramework Login';
        $this->_vista->error = 'CalimaFramework Login';
        $this->_vista->imprimirVista('index', 'usuario');  
        
        
    }  
    
    public function registro(){ 
        
        $this->_vista->titulo = 'CalimaFramework registro';
        $this->_vista->imprimirVista('registro', 'usuario');
        
        //$this->_sesion->iniciarSesion('_s', false);
        //echo$_SESSION['something'];
    }
    
    public function crearRegistro(){
        $datas = $this->cargaModelo('usuario');    
        if(isset($_POST['accept'])){
        $nombre=$_POST['nombre'];
        $email=$_POST['email'];
        $usuario=$_POST['nombre'];
        $clave=$this->_seg->cifrado($this->_seg->filtrarTexto($_POST['clave']));
        
         $datas->insertarRegistro(
                     $this->_seg->filtrarTexto($_POST['nombre']),
                     $this->_seg->filtrarTexto($_POST['email']),
                     '1',
                     $clave
                    );
                $this->_ayuda->redireccionUrl('usuario/registro');
                //$_POST['option1']=false;
        }
    }
    
    public function valida(){
        if(isset($_POST['usuario'])){
        $usuario=$_POST['usuario'];
        $clave=$this->_seg->cifrado($this->_seg->filtrarTexto($_POST['clave']));
        
        $datosUser = $this->cargaModelo('usuario');
        $valida=$datosUser->seleccionUsuario($usuario, $clave);        
        if($valida){
            $this->_sesion->iniciarSesion('_s', false);
            $_SESSION['usuario']=$usuario;
            $this->_ayuda->redireccionUrl('');          
        }
        $this->_ayuda->redireccionUrl('usuario');
        
        }
    }
    
     public function cerrarSesion(){
        $this->_sesion->iniciarSesion('_s', false);
        session_destroy();
        $this->_sesion->destruir('usuario');
        $this->_ayuda->redireccionUrl('usuario');
        
    }
}
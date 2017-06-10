<?php
interface MY_Controller_Interface{

    /**
     * ADD backend_* METHOD TO THE DESIRED CONTROLLER IN YOUR MODULE TO INITIALIZE THE BACKEND
     * @author CARL LOUIS MANUEL
     * --------------------------------------------------------------------------------------
     * class AppModule extends MY_Controller{
     *      public backend_list(){
     *          echo 123;
     *      }
     * }
     */
    public function BACKEND();
}
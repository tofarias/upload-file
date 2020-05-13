<?php
    class Upload{

        private $file;
        private $nameFileUploaded;
        private $allowedExtension;
        private $errorMessage;

        public function __construct(Array $file){
            $this->file = $file;
            $this->errorMessage = "";
        }

        public function getFullName() : String{
            return $this->file[INPUT_FILE]['name'];
        }

        public function getType() : String{
            return $this->file[INPUT_FILE]['type'];
        }

        public function getTmpName() : String{
            return $this->file[INPUT_FILE]['tmp_name'];
        }

        public function getExtension(){
            return pathinfo($this->getFullName(), PATHINFO_EXTENSION);
        }

        public function getName(){
            return pathinfo($this->getFullName(), PATHINFO_FILENAME);
        }

        public function getSize() : int{
            return $this->file[INPUT_FILE]['size'];
        }

        public function getSuccessMessage() : String{
            return '<p style="color:green">Ok, nenhum erro encontrado!</p>';
        }

        public function getNameFileUploaded(){
            return $this->nameFileUploaded;
        }

        public function setAllowedExtension(Array $extensions) : void{
            $this->allowedExtension = $extensions;
        }

        public function getErrorMessage(){
            return $this->errorMessage;
        }

        public function moveUploadedFile(String $targetDir, String $nameFile) : bool {

            $this->validatePostFile();
            $this->validatePostFileExtension();

            if( $this->hasError() ){
                return false;
            }
            
            $uploadDir = __DIR__.DIRECTORY_SEPARATOR.$targetDir.DIRECTORY_SEPARATOR;

            $name = empty($nameFile) ? $this->getName() : $nameFile.'.'.$this->getExtension();

            $targetFile = $uploadDir.$name.strtotime("now").'.'.$this->getExtension();

            if( move_uploaded_file($this->getTmpName(), $targetFile) ){
                $this->nameFileUploaded = $targetFile;
                return true;
            }
            return false;
        }

        private function hasExtensionAllowed(){
            if( in_array( $this->getExtension(), $this->allowedExtension ) ){
                return true;
            }
            return false;
        }

        private function validatePostFileExtension(){

            if( !$this->hasError() ){
                if( !$this->hasExtensionAllowed() ){
                    $this->errorMessage = "Extenção de arquivo (.".$this->getExtension().") não permitida!";
                }
            }
        }

        private function validatePostFile() : void {

            if( !$this->hasError() ){

                switch ( $this->file[INPUT_FILE]['error'] ) {
                    case 1:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_INI_SIZE:</b> o arquivo enviado excede o limite definido na diretiva upload_max_filesize ('.ini_get('upload_max_filesize').') do php.ini!</p>';
                        break;
                    case 2:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_FORM_SIZE:</b> o arquivo excede o limite definido em MAX_FILE_SIZE no formulário HTML!</p>';
                        break;
                    case 3:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_PARTIAL:</b> o upload do arquivo foi feito parcialmente.!</p>';
                        break;
                    case 4:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_NO_FILE:</b> Nenhum arquivo foi enviado!</p>';
                        break;
                    case 6:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_NO_TMP_DIR:</b> Pasta temporária ausênte!</p>';
                        break;
                    case 7:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_CANT_WRITE:</b> Falha em escrever o arquivo em disco!</p>';
                        break;
                    case 8:
                        $this->errorMessage = '<p style="color:red"><b>UPLOAD_ERR_EXTENSION:</b> Uma extensão do PHP interrompeu o upload do arquivo!</p>';
                        break;
                }
            }
        }

        private function hasError() : bool {
            return $this->errorMessage != "";
        }
    }

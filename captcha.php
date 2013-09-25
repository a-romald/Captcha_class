<?php

class Captcha {

    private $width = 150;
    private $height = 70;
    private $fonts_dir = 'fonts';
    private $code_length = 5;
    private $stripes_number = 4;
    
    /**
     * Method to create image of captcha
     */
    public function create() {
        
        // Components for RGB-color of dots and symbols
        $figures = array('20','30','40','50','60','70','80','90','100','110','120','130','140','150','160','170','180','190','200','210','220');
        
        //1. Create rectangle for captcha
        $im = imagecreatetruecolor($this->width, $this->height);
        
        $r = mt_rand(133,255);	
        $g = mt_rand(133,255);
        $b = mt_rand(133,255);
        
        $background = imagecolorallocate($im,$r,$g,$b);	
        
        imagefilledrectangle($im, 0, 0, $this->width, $this->height, $background);
        
        
        //2. Fill background with multicolor dots
        for($j=0; $j<$this->width; $j++)
        {
            for($i=0; $i<($this->height * $this->width)/1500; $i++)
            {
              // Create random color
              $color = imagecolorallocatealpha(
                            $im,
                            $figures[rand(0,count($figures)-1)],
                            $figures[rand(0,count($figures)-1)],
                            $figures[rand(0,count($figures)-1)],
                            rand(10,30)); 
              // Make random dot of random color
              imagesetpixel($im,
                            rand(0, $this->width),
                            rand(0,$this->height),
                            $color);
            }
        }
        
        
        //3. Output string of symbols        
        $str = $this->generate_str();
    	
    	$_SESSION['str_cap'] = $str;
    	
    	$fonts = $this->get_fonts();
    	
    	//$color = imagecolorallocate($im, 7, 7, 7);
        $x = 20; //Offset of string of symbols from the left
    	
    	for($i = 0; $i < strlen($str); $i++) {
    		
    		$color = imagecolorallocatealpha(
                            $im,
                            $figures[rand(0,count($figures)-1)],
                            $figures[rand(0,count($figures)-1)],
                            $figures[rand(0,count($figures)-1)],
                            rand(10,30)); //If multicolor symbols
                            
            $n = mt_rand(0,count($fonts)-1);//For choice of font
    		$font = $this->fonts_dir."/".$fonts[$n];
            
    		$size = mt_rand(20,35); //Size of letters range
    		$angle = mt_rand(-30,30);
    		$y = mt_rand(40,45);
    		
    		imagettftext($im,$size,$angle,$x,$y,$color,$font,$str[$i]);
    		
    		$x = $x + $size - 5;
    	}
        
        
        //4. Output of random lines
    	for($c = 0; $c < $this->stripes_number; $c++) {
    		$x1 = mt_rand(0,intval($this->width*0.1));
    		$x2 = mt_rand(intval($this->width*0.8),$this->width);
    		
    		$y1 = mt_rand(0,intval($this->height*0.6));
    		$y2 = mt_rand(($this->height*0.3),$this->height);
    		
            //$black = imagecolorallocate($im,7,7,7); //black color of lines
            //Make random color of a line
            $c1 = mt_rand(1,255);	
            $c2 = mt_rand(1,255);
            $c3 = mt_rand(1,255);
            $col = imagecolorallocate($im,$c1,$c2,$c3);
            	
    		imageline($im, $x1, $y1, $x2, $y2, $col);
    	}
        
        
        //5. Output of final image
        header('Content-Type:image/png');
        imagepng($im);
        imagedestroy($im);
    }
    
    
    
    
    
    
    /**
     * Method to generate string
     */
    private function generate_str() {
		$str = "23456789abcdefghijkmnpqstuvxyz";
		
		$strLength = strlen($str) - 1;
		
		$strSgen = '';
		
		for($i = 0; $i < $this->code_length; $i++) {
			$x = mt_rand(0,$strLength);
			
			if($i !== 0) {
				if($strSgen[strlen($strSgen)-1] == $str[$x]) {
					$i--;
					continue;
				}
			}
			$strSgen .=$str[$x];
		}
		return $strSgen;
	}
    
    
    /**
     * Method for random choice of a font for each symbol
     */
    private function get_fonts() {
		
		$d = opendir($this->fonts_dir);
	
		$fonts = array();
		while(FALSE !==($file = readdir($d))) {
			
			if($file == 'Thumbs.db' || $file == '..' || $file == '.') {
				continue;
			}
			$fonts[] = $file;
		}
		
		return $fonts;
	}


}
?>
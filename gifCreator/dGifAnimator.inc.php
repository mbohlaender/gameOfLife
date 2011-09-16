<?php
/***************************************************************
	dGifAnimator 1.0
	
	Allows you to dinamically create animated GIFs with PHP,
	without having to install any 3rd party extension or software.
	
	Release date: 04/04/2006
	Author: Alexandre Tedeschi (d)
	Email:  alexandrebr AT gmail DOT com
	
	Features:
	- Optimizes file size, using local pallete only when necessary
	- Allows looping
	
	Requirements:
	- This class requires the class dStream.
	- This class DOES NOT need GD support
	
	Methods:
	- addFile($filename): Add a GIF file to the animation
	- setDefaultConfig($type, $value): Set default configuration
	- setFrameConfig($type, $value [,$frame_idx]): Set config
	  for especific frame, or for the next frame added.
	- setLoop($loopTimes): How many times the animation will loop
	- build([$filename]): Build GIF to $filename or to the output
	
	Configurations allowed:
	- transparent_color: Transparent Index
	- delay_ms: Time to show each frame
	
	Loops:
	- If 0 (zero), will loop forever
	- If any number, will loop (n) times
	- If false or null, will NOT loop
	
	Problems:
	- Uses too much memory (Not TOO MUCH, but memory usage is
	  not optimized yet.. If you use this too many times, with
	  animations too big, your host probably won't be happy)
	- No error handling. I tested it only by images generated
	  by PHP_GD and Corel Photo-Paint 12.
***************************************************************/
require_once "dStream.inc.php";

class dGifAnimator{
	var $loop        = 0; # 0=Forever, or the number. (If you don't want to loop, set to FALSE)
	var $file_list   = Array();
	var $frameConfig = Array();
	var $defaultCfg  = Array(
			'transparent_color'=>null,
			'delay_ms'=>20
		);
	
	/** These are public **/
	Function addFile($filename){
		$this->file_list[] = $filename;
	}
	Function setFrameConfig($type, $value, $frame_index=false){
		// Configurations available:
		// - transparent_color (transparent index)
		// - delay_ms (1/100ths of a second)
		
		// If no Frame Index given, set the config for the next
		if($frame_index === false || $frame_index === null)
			$frame_index = sizeof($this->file_list);
		
		$this->frameConfig[$frame_index][$type] = $value;
	}
	Function setDefaultConfig($type, $value){
		$this->defaultCfg[$type] = $value;
	}
	Function setLoop($loop){
		$this->loop = $loop;
	}
	Function build($filename=false){
		$information   = Array();
		$headerInfo    = Array();
		$imageBlocks   = "";
		
		for($x = 0; $x < sizeof($this->file_list); $x++){
			$information = $this->getGifContents($this->file_list[$x]);
			if($x == 0)
				$headerInfo = $information['Header'];
			
			if($information['Header']['width'] > $headerInfo['width'])
				$headerInfo['width'] = $information['Header']['width'];
			
			if($information['Header']['height'] > $headerInfo['height'])
				$headerInfo['height'] = $information['Header']['height'];
			
			if($information['Header']['GCT_Resolution'] > $headerInfo['GCT_Resolution'])
				$headerInfo['GCT_Resolution'] = $information['Header']['GCT_Resolution'];
			
			$useLocalPallete = false;
			if($information['Header']['GCTPallete'] != $headerInfo['GCTPallete']){
				$useLocalPallete = true;
				$information['Image']['LCTPallete'] = $information['Header']['GCTPallete'];
			}
			
			$imageBlocks .= $this->mountGraphicControl(isset($this->frameConfig[$x])?$this->frameConfig[$x]:Array());
			$imageBlocks .= $this->mountImageBlock($information['Image'], $useLocalPallete);
			
			unset($information);
		}
		
		if($filename){
			$fh = fopen($filename, "w");
			fwrite($fh, $this->mountHeader($headerInfo, true));
			fwrite($fh, $this->mountApplicationExtension());
			fwrite($fh, $imageBlocks);
			fwrite($fh, $this->mountTrailer());
			fclose($fh);
		}
		else{
			echo $this->mountHeader($headerInfo, true);
			echo $this->mountApplicationExtension();
			echo $imageBlocks;
			echo $this->mountTrailer();
		}
	}
	
	/** These are private **/
	Function getGifContents($filename){
		$s = new dStream;
		$s->add_stream(file_get_contents($filename));
		
		// Get header
		$r['signature'] = $s->get_string(3); # Signature (GIF)
		$r['version']   = $s->get_string(3); # Version   (87a or 89a)
		$r['width']     = $s->get_UI16(); # Width  (In Pixels)
		$r['height']    = $s->get_UI16(); # Height (In Pixels)
		$r['GCT_Flag']       = $s->get_bits(1);           # Global Color Table Flag (GCTF)
		$r['GCT_Resolution'] = $s->get_bits(3)+1;         # Color Resolution
		$r['GCT_Sort']       = $s->get_bits(1);           
		$r['GCT_Size']       = pow(2, 1+$s->get_bits(3)); # Size of Global Color Table: 2^(1+n)
		$r['background']   = $s->get_UI8(); # Transparent background index
		$r['pixel_aspect'] = $s->get_UI8(); # Really, I don't know... I think it's deprecated.
		
		// If defined a Global Color Table, get it!
		if($r['GCT_Flag'])
			for($x = 0; $x < $r['GCT_Size']; $x++)
				$r['GCTPallete'][$x] = Array($s->get_UI8(), $s->get_UI8(), $s->get_UI8()); # Red, Blue, Green
		
		// Get Image Block
		$i['signature'] = $s->get_string(1); # Signature (0x2c [comma])
		$i['left']      = $s->get_UI16();    # Left position (In Pixels)
		$i['top']       = $s->get_UI16();    # Top  position (In Pixels)
		$i['width']     = $s->get_UI16();    # Frame width
		$i['height']    = $s->get_UI16();    # Frame height
		$i['LCT_Flag']       = $s->get_bits(1);  # Local Color Table Flag (LCTF)
		$i['LCT_Interlace']  = $s->get_bits(1);  # Interlace Flag
		$i['LCT_Sort']       = $s->get_bits(1);  # Sort Flag to Global Color Table
		/** Reserved **/       $s->skip_bits(2);
		$i['LCT_Size']       = pow(2, 1+$s->get_bits(3)); # Size of Local Color Table: 2^(1+n)
		
		// If defined a Local Color Table, get it!
		if($i['LCT_Flag'])
			for($x = 0; $x < $i['LCT_Size']; $x++)
				$r['LCTPallete'][$x] = Array($s->get_UI8(), $s->get_UI8(), $s->get_UI8()); # Red, Blue, Green
		
		$i['LZW_Minimum'] = $s->get_UI8(); # LZW Minimum Code Size
		$i['image_data']  = "";
		
		$x = 0;
		while(++$x < 10000){ // Se o arquivo tiver mais que 2.560.000 bytes, vai dar erro.
			$blockSize  = $s->get_UI8();
			if($blockSize == 0)
				break;
			$i['image_data'] .= $s->get_string($blockSize);
		}
		$r['trailer'] = $s->get_string(1); # Trailer (0x3b)
		
		return Array("Header"=>$r, "Image"=>$i);
	}
	Function mountHeader($imageInfo, $useGCT=true){
		$flags = "00000000";
		
		// Set resolution
		// (if pallete size =   4, then resolution is 0)
		// (if pallete size =   8, then resolution is 1)
		// (if pallete size =  16, then resolution is 2)
		// (if pallete size =  32, then resolution is 4)
		// (if pallete size =  64, then resolution is 5)
		// (if pallete size = 128, then resolution is 6)
		// (if pallete size = 256, then resolution is 7)
		$resol = sprintf("%03d", decbin($imageInfo['GCT_Resolution'])-1);
		$flags = substr_replace($flags, $resol, 1, 3);
		
		$GCT   = "";
		if($useGCT){
			$GCT_Size = count($imageInfo['GCTPallete']);
				
				if($GCT_Size == 2)   $size = "000";
			elseif($GCT_Size == 4)   $size = "001";
			elseif($GCT_Size == 8)   $size = "010";
			elseif($GCT_Size == 16)  $size = "011";
			elseif($GCT_Size == 32)  $size = "100";
			elseif($GCT_Size == 64)  $size = "101";
			elseif($GCT_Size == 128) $size = "110";
			elseif($GCT_Size == 256) $size = "111";
			else
				die("Pallete size MUST BE 2, 4, 8, 16, 32, 64, 128 or 256. It is $GCT_Size");
			
			$flags[0] = '1';
			$flags = substr_replace($flags, $size, 5); # Size of Global Color Table: 2^(1+n)
			
			for($x = 0; $x < $GCT_Size; $x++){
				$GCT .= chr($imageInfo['GCTPallete'][$x][0]); # Pallete color Red
				$GCT .= chr($imageInfo['GCTPallete'][$x][1]); # Pallete color Green
				$GCT .= chr($imageInfo['GCTPallete'][$x][2]); # Pallete color Blue
			}
		}
		
		$ib  = "GIF89a"; # Signature (0x2c)
		$ib .= pack("v", $imageInfo['width']);   # Left position
		$ib .= pack("v", $imageInfo['height']);  # Top  position
		$ib .= chr(bindec($flags));              # Flags
		$ib .= chr($imageInfo['background']);    # Background transparent
		$ib .= chr($imageInfo['pixel_aspect']);  # Pixel aspect
		$ib .= $GCT;                             # Pallete
		
		return $ib;
	}
	Function mountApplicationExtension(){
		if($this->loop === false || $this->loop === null)
			return "";
		
		$ib  = chr(0x21).chr(0xff).chr(0x0b);
		$ib .= "NETSCAPE2.0";
		$ib .= chr(3); # Block length
		$ib .= chr(1); # Unknown
		$ib .= chr($this->loop);
		$ib .= chr(0); # Unknown
		$ib .= chr(0); # Block terminator
		
		return $ib;
	}
	Function mountGraphicControl($details=Array()){
		/** Set default values for details **/
		if(!isset($details['transparent_color']) || $details['transparent_color']===false)
			$details['transparent_color'] = $this->defaultCfg['transparent_color'];
		if(!isset($details['delay_ms']))
			$details['delay_ms'] = $this->defaultCfg['delay_ms'];
		
		$flags = "00000000";
		// Disposal method? Not implemented
		
		$flags[6] = '0';                                            # User input flag
		$flags[7] = ($details['transparent_color']!==null)?'1':'0'; # Transparent Color Flag
		
		$ib  = chr(0x21); # Extension Introducer
		$ib .= chr(0xf9); # Graphics Control Label
		$ib .= chr(0x04); # Block Size
		$ib .= chr(bindec($flags)); # Flags
		$ib .= pack("v", $details['delay_ms']);    # Delay (1/100ths of a second)
		$ib .= chr($details['transparent_color']); # Transparent Color Index
		$ib .= chr(0);
		
		return $ib;
	}
	Function mountImageBlock($imageInfo, $useLCT=false){
		$flags = "00000000";
		$LCT   = "";
		if($useLCT){
			$LCT_Size = count($imageInfo['LCTPallete']);
			    
				if($LCT_Size == 2)   $size = "000";
			elseif($LCT_Size == 4)   $size = "001";
			elseif($LCT_Size == 8)   $size = "010";
			elseif($LCT_Size == 16)  $size = "011";
			elseif($LCT_Size == 32)  $size = "100";
			elseif($LCT_Size == 64)  $size = "101";
			elseif($LCT_Size == 128) $size = "110";
			elseif($LCT_Size == 256) $size = "111";
			else
				die("Pallete size MUST BE 2, 4, 8, 16, 32, 64, 128 or 256. It is $LCT_Size");
			
			$flags[0] = '1';
			$flags = substr_replace($flags, $size, 5); # Size of Local Color Table: 2^(1+n)
			
			for($x = 0; $x < $LCT_Size; $x++){
				$LCT .= chr($imageInfo['LCTPallete'][$x][0]); # Pallete color Red
				$LCT .= chr($imageInfo['LCTPallete'][$x][1]); # Pallete color Green
				$LCT .= chr($imageInfo['LCTPallete'][$x][2]); # Pallete color Blue
			}
		}
		
		# Split image data into blocks
		if(strlen($imageInfo['image_data']) > 254){
			$newImageInfo = "";
			while(strlen($imageInfo['image_data']) > 254){
				$first254 = substr($imageInfo['image_data'], 0, 254);
				$imageInfo['image_data'] = substr($imageInfo['image_data'], 254);
				$newImageInfo .= chr(254).$first254;
			}
			if(strlen($imageInfo['image_data'])){
				$newImageInfo .= chr(strlen($imageInfo['image_data']));
				$newImageInfo .= $imageInfo['image_data'];
			}
			$imageInfo['image_data'] = $newImageInfo;
		}
		else
			$imageInfo['image_data'] = chr(strlen($imageInfo['image_data'])).$imageInfo['image_data'];
		
		$ib  = ","; # Signature (0x2c)
		$ib .= pack("v", $imageInfo['left']); # Left position
		$ib .= pack("v", $imageInfo['top']);  # Top  position
		$ib .= pack("v", $imageInfo['width']); # Width
		$ib .= pack("v", $imageInfo['height']);# Height
		$ib .= chr(bindec($flags));
		$ib .= $LCT;
		$ib .= pack("C", $imageInfo['LZW_Minimum']);
		$ib .= $imageInfo['image_data'];
		$ib .= chr(0);
		
		return $ib;
	}
	Function mountTrailer(){
		return "\x3b";
	}
}
?>

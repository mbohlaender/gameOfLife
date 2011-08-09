<?php
// Class dStream
// - Based on a PERL Example found on the internet
// - This class only allows you to READ data.
// - Should be added support to write it?

class dStream{
	var $data   = "";
	var $pos    = 0;
	var $binpos = 0;
	
	function add_stream($data){
		$this->data .= $data;
	}
	function get_string($size){
		$ret = substr($this->data, $this->pos, $size);
		$this->pos    += $size;
		$this->binpos += ($size*8);
		return $ret;
	}
	function get_UI8(){
		$ret = substr($this->data, $this->pos, 1);
		$ord = unpack('C', $ret);
		$this->pos    += 1;
		$this->binpos += 1*8;
		return $ord[1];
	}
	function get_UI16(){
		$ret = substr($this->data, $this->pos, 2);
		$ord = unpack('v', $ret);
		$this->pos    += 2;
		$this->binpos += 2*8;
		return $ord[1];
	}
	function get_UI32(){
		$ret = substr($this->data, $this->pos, 4);
		$ord = unpack('L', $ret);
		$this->pos    += 4;
		$this->binpos += 4*8;
		return $ord[1];
	}
	function get_bits($size, $signed=false){
		$bytesToGetFirst = $this->binpos?intval($this->binpos/8):0;
		$bytesToGetFinal = (!(($this->binpos+$size)%8))?($this->binpos+$size)/8:intval(($this->binpos+$size)/8)+1;
		$bytesData = substr($this->data, $bytesToGetFirst, $bytesToGetFinal-$bytesToGetFirst);
		$binData   = '';
		for($x = 0; $x < strlen($bytesData); $x++)
			$binData .= sprintf("%08d", decbin(ord($bytesData[$x])));
		
		$bits = substr($binData, $this->binpos%8, $size);
		$this->binpos += $size;
		
		$div8 = $this->binpos/8;
		if(is_int($div8))
			$this->pos = $div8;
		else
			$this->pos = intval($div8)+1;
		
		$sign = 1;
		if($signed){
			$sign = substr($bits, 0, 1)?-1:1;
			$bits = substr($bits, 1);
		}
		return bindec($bits)*$sign;
	}
	function get_sbits($size){
		return $this->get_bits($size, true);
	}
	function skip_bytes($size){
		$this->pos    += $size;
		$this->binpos += ($size*8);
	}
	function skip_bits($size){
		$this->binpos += $size;
		
		$div8 = $this->binpos/8;
		if(is_int($div8))
			$this->pos = $div8;
		else
			$this->pos = intval($div8)+1;
	}
	
	// Extra, estruturas adicionadas por mim.
	function get_RECT(){
		$nbits = $this->get_bits(5);
		return Array(
			'nbits'=>$nbits,
			'xmin'=>$this->get_sbits($nbits),
			'xmax'=>$this->get_sbits($nbits),
			'ymin'=>$this->get_sbits($nbits),
			'ymax'=>$this->get_sbits($nbits)
		);
	}
}


/**
	Example to get information from a SWF File:
/
$filename = "dr3i_v2.swf";
$a = fopen($filename, "r");
$data = fread($a, 3+1+4+9+2+2);
fclose($a);

$s = new dStream;
$s->add_stream($data);
echo "Signature:  " . $s->get_string(3) . "\n";
echo "Version:    " . $s->get_UI8()     . "\n";
echo "FileLength: " . $s->get_UI32()    . " (".filesize($filename).")\n";

$rect = $s->get_RECT();
echo "Rectangle:  X:$rect[xmin]-$rect[xmax]  Y:$rect[ymin]-$rect[ymax]\n";
echo "Rate:       " . ($s->get_UI16()/256) . "\n";
echo "Frames:     " . $s->get_UI16() . "\n";
echo "Width(px):  " . intval(($rect['xmax']-$rect['xmin'])/20) . "\n";
echo "Height(px): " . intval(($rect['ymax']-$rect['ymin'])/20) . "\n";
/***/
?>
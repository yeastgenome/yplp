<?php
/**
 * Encryptor.php
 *
 * Holds all functions for encrypting and decrypting
 * @author Florian Leitner <florian.leitner AT student DOT tugraz DOT at>
 * @version 1.0
 * @package YPLP
 * @copyright (c) 2009 Yeast Genetics and Molecular Biology Group University Graz
 */

class Encryptor
{
  /**
   * @var array
   */
  var $crypt_array = array(
    '0000'  => 't',
    '000'  => 'a',
    '00'  => 'z',
    '11'  => 'e',
    '22'  => 'k',
    '33'  => 'g',
    '44'  => 'q',
    '55'  => 'm',
    '66'  => 's',
    '77'  => 'i',
    '88'  => 'c',
    '99'  => 'o',
    
    '01'  => 'b',
    '02'  => 'd',
    '03'  => 'f',
    '04'  => 'h',
    '05'  => 'j',
    '06'  => 'l',
    '07'  => 'n',
    '08'  => 'p',
    '09'  => 'r'
  );
  
  /**
   * Encrypt a string
   *
   * @param string $string
   * @return string
   */
  function Encrypt($string)
  {
    $newString = '';
    $LenString = strlen($string);
  
    for ($i = 0; $i < $LenString; $i++)
    {
      $newSP = ord($string{$i}) + $i;
      $newString .= str_pad($newSP, 4, '0', STR_PAD_LEFT);
    }
    
    // now pack the string
    $searchCArray = array_keys($this->crypt_array);
    
    return str_replace($searchCArray, $this->crypt_array, $newString);
  }
  
  /**
   * Decrypt a string
   *
   * @param string $string
   * @return string
   */
  function Decrypt($string)
  {
    $newString = '';
  
    $replaceCArray = array_keys($this->crypt_array);
    $string = str_replace($this->crypt_array, $replaceCArray, $string);
  
    $LenString = strlen($string) / 4;
    $offset = 0;
  
    for ($i = 0; $i < $LenString; $i++)
    {
      $newString .= chr(substr($string, $offset, 4) - $i);
      $offset += 4;
    }
    
    return $newString;
  }
}
?>
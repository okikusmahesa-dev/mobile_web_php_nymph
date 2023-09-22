/**
 * À·°ªÀ  null ÀÁ üũÇ´Ù */
function isNull(input){
       if (input.value == null || input.value == ""){
             return true;
       }else{
             return false;
       }
}
/**
 * À·°ªÀ ½ºÆÀ½º À¿ÜÇÀ¹ÌִÂ°ªÀ À´ÂöũÇ´Ù * if (isEmpty(form.keyword)){
 *       alert(!'°ª; À·ÂϿ©Á¼¼¿ä;
 * }
 */
function isEmpty(input){
       if (input.value == null || input.value.replace(/ /gi,"") == ""){
             return true;
       }else{
             return false;
       }
}
/**
 * À·°ª¿¡ Ưd ¹®À°¡ À´ÂöũÇ´Â·Î÷ç* Ưd¹®À¸¦ Ç¿ë°íÍö;¶§ »çÇ¼öÀ´Ù * if (containsChars(form.name, "!,*&^%$#@~;")){
 *       alert(!"Ư¼öڸ¦ »çÇ¼ö½4ϴÙ);
 * }
 */
function containsChars(input, chars){
       for (var i=0; i < input.value.length; i++){
             if (chars.indexOf(input.value.charAt(i)) != -1){
                    return true;
             }
       }
       return false;
}
/**
 * À·°ªÀ Ưd ¹®À¸¸8·ÎµǾî´ÂöũÇ¸ç* Ưd¹®À¸¸; Ç¿ë·ÁÇ¶§ »çÇ´Ù
 * if (containsChars(form.name, "ABO")){
 *    alert(!"Ç¾×ü¿¡´ÂA,B,O ¹®À¸¸ »çÇ¼ö½4ϴÙ");
 * }
 */
function containsCharsOnly(input, chars){
       for (var i=0; i < input.value.length; i++){
             if (chars.indexOf(input.value.charAt(i)) == -1){
                    return false;
             }
       }
       return true;
}
/**
 * À·°ªÀ ¾ËĺªÀÁ üũ
 * ¾Ʒ¡ isAlphabet() ºÎÍisNumComma()±îÀ ¸޼ҵ尡 ÀÁ ¾²À´Â°æ¿¡´Â * var chars º¯¼öglobal º¯¼ö¼±¾ðíçÇµµ·ÏÇ´Ù
 * var uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
 * var lowercase = "abcdefghijklmnopqrstuvwxyz";
 * var number = "0123456789";
 * function isAlphaNum(input){
 *       var chars = uppercase + lowercase + number;
 *    return containsCharsOnly(input, chars);
 * }
 */
function isAlphabet(input){
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
       return containsCharsOnly(input, chars);
}
/**
 * À·°ªÀ ¾Ëĺª ´빮ÀÀÁ üũÇ´Ù */
 function isUpperCase(input){
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
       return containsCharsOnly(input, chars);
 }
/**
 * À·°ªÀ ¾Ëĺª ¼ҹ®ÀÀÁ üũÇ´Ù */
function isLowerCase(input){
       var chars = "abcdefghijklmnopqrstuvwxyz";
       return containsCharsOnly(input, chars);
}
/**
 * À·°ªÀ ¼ýÀ´ÂöũÇ´Ù
 */
function isNumer(input){
       var chars = "0123456789";
       return containsCharsOnly(input, chars);
}
/**
 * À·pªÀ ¾Ëĺª, ¼ýµǾî´ÂöũÇ´Ù */
function isAlphaNum(input){
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
       return containsCharsOnly(input, chars);
}
/**
 * À·°ªÀ ¼ýë"-" ·ÎµǾî´ÂöũÇ´Ù * Àȭ¹ø ¿ì¹ø°è¹ø -  üũÇ¶§ /¿ë´Ù */
function isNumDash(input){
       var chars = "-0123456789";
       return containsCharsOnly(input, chars);
}
/**
 * À·°ªÀ ¼ý޸¶',' ·ÎµǾî´ÂöũÇ´Ù */
function isNumComma(input){
       var chars = ",0123456789";
       return containsCharsOnly(input, chars);
}
/**
 * À·°ªÀ »çÀ°¡ dÀÇ Æ¸ËÇ½ÄÎöũ
 * À¼¼Ç format Ç½Äº À¹ٽºũ¸³ƮÀ 'reqular expression!' Â°í´Ù */
function isValidFormat(input, format){
       if (input.value.search(format) != -1){
             return true; // ¿ùٸ¥ Æ¸ä½Ä       }     
       return false;
}
/**
 * À·°ªÀ À¸ÞÏÇ½ÄÎöũÇ´Ù * if (!isValidEmail(form.email)){
 *       alert(!"¿ùٸ¥ À¸ÞÏÁ¼Ұ¡ ¾ƴմϴÙ);
 * }
 */
function isValidEmail(input){
       var format = /^((\w|[\-\.])+)@((\w|[\-\.])+)\.([A-Za-z]+)$/;
       return isValidFormat(input, format);
}
/**
 * À·°ªÀ Àȭ¹øüÀ-¼ýÀ)ÀÁ üũÇ´Ù */
function isValidPhone(input){
       var format = /^(\d+)-(\d+)-(\d+)$/;
       return isValidFormat(input, format);
}
/**
 * À·°ªÀ ¹ÙÌ® ±æ¸¦ ¸®ÅÇ´Ù
 * if (getByteLength(form.title) > 100){
 *    alert(!"f¸ñÇ±Û50À (¿µ¹® 100À) À»ó·ÂҼö½4ϴÙ);
 * }
 */
function getByteLength(input){
       var byteLength = 0;
       for (var inx = 0; inx < input.value.charAt(inx); inx++)     {
             var oneChar = escape(input.value.charAt(inx));
             if (oneChar.length == 1){
                    byteLength++;
             }else if (oneChar.indexOf("%u") != -1){
                    byteLength += 2;
             }else if (oneChar.indexOf("%") != -1){
                    byteLength += oneChar.length / 3;
             }
       }
       return byteLength;
}
/**
 * À·°ª¿¡¼­ Ä¸¶¸¦ ¾ø
 */
function removeComma(input){
       return input.value.replace(/,/gi,"");
}
/**
 * ¼±ÅµÈ¶ó9öÌÀ´ÂöũÇ´Ù */
function hasCheckedRadio(input){
       if (input.length > 1){
             for (var inx = 0; inx < input.length; inx++){
                    if (input[inx].checked) return true;
             }
       }else{
             if (input.checked) return true;
       }
       return false;
}
/**
 * ¼±ÅµÈüũ¹ڽº°¡ À´Âöũ
 */
function hasCheckedBox(input){
       return hasCheckedRadio(input);
}



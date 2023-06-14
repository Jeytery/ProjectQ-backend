<?php

/*
   The functions for the API
   ProQ. 2023
 */

// Tokens and password helpers

function getBase64EncodedData( $encode, $data ) {
    $ret_val = '';
    $encode_size = strlen( $encode );
    $data_size = strlen( $data );
    if ( $encode_size === 0 || $data_size === 0 ) {
        return $ret_val;
    }
    for ( $i = 0; $i < $data_size; $i ++ ) {
        $ret_val .= (chr( ord( $data[ $i ] ) ^ ord( $encode[ $i % $encode_size ] ) ));
    }
    return base64_encode( $ret_val );
}

function getBase64DecodedData( $decode, $encoded_data ) {
    $ret_val = '';
    $decode_size = strlen( $decode );
    $data = base64_decode( $encoded_data );
    $data_size = strlen( $data );
    if ( $decode_size === 0 || $data_size === 0 ) {
        return $ret_val;
    }
    for ( $i = 0; $i < $data_size; $i ++ ) {
        $ret_val .= (chr( ord( $data[ $i ] ) ^ ord( $decode[ $i % $decode_size ] ) ));
    }
    return $ret_val;
}

function getTokenOrPasswordData( $salt = "I6g5NM3L47", $length = 16 ) {
    return substr( str_shuffle( strtolower( sha1( $salt . rand() . time() ) ) ), 0, $length );
}

// SQL queries helpers

$isWhereFounded = false;

function getWhereParam($paramName, $isLiked, $paramValue) {
    global $isWhereFounded;
    $where_param = '';
    if ($paramName && $paramValue && $paramValue != '%') {
        $where_param = getWhereOrAndKeyword();
        $where_param .= $paramName . ($isLiked == true ? " LIKE '" . $paramValue . "'" : " = " . $paramValue);
    }
    return $where_param;
}

function getWhereBetweenParam($paramName, $paramValue_1, $paramValue_2) {
    global $isWhereFounded;
    $where_param = '';
    if ($paramName && $paramValue_1 && $paramValue_2) {
        $where_param = getWhereOrAndKeyword();
        $where_param .= $paramName . " BETWEEN '" . $paramValue_1 . "' AND '" . $paramValue_2 . "'";
    }
    return $where_param;
}

function getWhereOrAndKeyword() {
    global $isWhereFounded;
    $where_param = '';
    if ($isWhereFounded) {
        $where_param = " AND ";
    } else {
        $where_param = " WHERE ";
        $isWhereFounded = true;
    }
    return $where_param;
}

?>

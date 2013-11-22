<?php
	///////////////////////////////////////////////////
	//POSTされてきた文字列を返すメソッド
	//なければfaleseを返す
	///////////////////////////////////////////////////
	class PostChar{
		//英字の全角を半角にして返すメソッド
		function postEnglish($moji){
			if(isset($_POST["${moji}"]) && $_POST["${moji}"] != ""){
				$moji = htmlspecialchars($_POST["${moji}"], ENT_QUOTES);
				//全角文字を半角文字に変換
				$moji = mb_convert_kana($moji,"as");
                                return $moji;
			}else{
				return false;
			}
		}
		//日本語をそのまま返すメソッド
		function postJapanese($moji){
			if(isset($_POST["${moji}"]) && $_POST["${moji}"] != ""){
				$moji = htmlspecialchars($_POST["${moji}"], ENT_QUOTES);
				return $moji;
			}else{
				return false;
			}
		}
	}
?>
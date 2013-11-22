<?php
	ini_set( 'error_reporting', E_ALL );
	//**************************************
	//Gotu_MySQLクラス
	//**************************************
        /**
         * MySQLに接続する
         */
	class GotuMySQL{
		var $mysqli;
		var $stmt;
                var $result;
		function __construct(){
			//Windows
			$file = dirname(__FILE__)."/../../gotu.ini";
			if(!file_exists($file)){
				die("gotu.iniファイルが存在しません。");
			}else{
				$fp = fopen($file,"r");
				if(!$fp){
					die("gotu.iniファイルが存在しません。");
				}else{
					//trimは余計な空白などを削除
					$host_name=trim(fgets($fp));
					$user_name=trim(fgets($fp));
					$pass_word=trim(fgets($fp));
					$world=trim(fgets($fp));
				}
				fclose($fp);
			}
			//MySQLへ接続
			$this->mysqli = new mysqli($host_name,$user_name,$pass_word,$world);
			if (mysqli_connect_errno()) {
    			printf("Connect failed: %s\n", mysqli_connect_error());
    			exit();
			}
			
		}
                /**
                 * プリペアドステートメントクエリの処理
                 * @param type $sql 処理するプリペアドステートメントクエリの文字列
                 * @return type $stmt プリペアドステートメント
                 */
		function prepare($sql){
			$this->stmt = $this->mysqli->prepare($sql);
                        return $this->stmt;
		}
                /**
                 * クエリの処理
                 * @param type $sql 処理するクエリの文字列
                 * @return type $result 実行結果
                 */
		function query($sql){
                    $this->result = $this->mysqli->query($sql);
                    return $this->result;
		}
                /**
                 * 結果を一行ずつ返す
                 * @return type $row 連想配列
                 */
                function fetch(){
                    return $this->result->fetch_array(MYSQLI_BOTH);
                }
                /**
                 * 列数
                 * @return type int 列数
                 */
                function cols(){
                    return $this->mysqli->field_count;
                }
                /**
                 * 行数
                 * @return type int 行数
                 */
                function rows(){
                    return $this->result->num_rows;
                }
                /**
                 * 結果のメモリ解放
                 */
                function close(){
                    $this->result->close();
                }
                /**
                 * mysqlをclose
                 */
                function mysqlClose(){
                    mysqli_close($this->mysqli);
                }
	}

		/*
		function bind_param($type,$cols1){
			//マーカにパラメータをバインド
			$this->stmt->bind_param($type,$cols1);
			//クエリの実行
			$this->stmt->execute();
			$this->stmt->bind_result($)
		}*/
		///////////////////////////////////////////////////////////
		//Select クエリを実行します。これは結果セットを返します 
		///////////////////////////////////////////////////////////
?>
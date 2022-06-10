<?php
session_start();

$text =$_GET['text'];
$content = $_GET['content'];

$db = new PDO('mysql:host=localhost; dbname=demo', 'root', '');

try{
    $db->beginTransaction();

    $sql = 'INSERT INTO demo ( text , content ) VALUES( :text, :content );';
    
    // データベースに事前にSQLを登録する
    $stm = $db->prepare( $sql );
    
    
    $stm->bindParam( ':text', $text );
    $stm->bindParam( ':content', $content );
    
    // SQLを実行する
    $stm->execute();
    
    $sql = 'SELECT * FROM demo order by id DESC;';
    
    $stm = $db->prepare( $sql );
    
    // SQLを実行する
    $stm->execute();

    // 結果を取得する
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);

    $db->commit(); 
}catch ( Exception $e ) {
    // データベースエラーなので、ロールバックする
    $dbh->rollback();
    // XXX エラーメッセージを表示する
    echo "データベースエラー" . $e->getMessage();
}


$html = file_get_contents('comp.html');

$html_top = explode( '{{root}}', $html );


$html_bottom = explode( '{{/root}}', $html_top[1] );

$html_middle = '';
 
// データ件数分、リスト部分を作る
foreach( $result as $line ){

    // 一行分のテンプレートを作る
    $html_line = $html_bottom[0];

    $html_line = str_replace('{{text}}',$line['text'],$html_line);
    $html_line = str_replace('{{content}}',$line['content'],$html_line);

    // 真ん中部分のhtmlとして登録する
    $html_middle= $html_middle . $html_line;
}
// 作った真ん中の部分と、前後を貼り合わせる
$html = $html_top[0] . $html_middle . $html_bottom[1];


print($html);
?>
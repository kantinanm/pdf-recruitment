<?php
	require('fpdf/fpdf.php');

		$r=255;
		$g=255;
		$b=255;

	if($_GET["p"]==""){
		return "";
	}

	$genaral_data_url="";
	$education_data_url="";
	$qry_str ="? customize parameter here =".$_GET["p"];
	
	$ch = curl_init();

	// Set query data here with the URL
	curl_setopt($ch, CURLOPT_URL, $genaral_data_url . $qry_str); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	$content = curl_exec($ch);
	//curl_close($ch);
	//print $content;
	curl_close($ch);
	//echo $content;
	$obj = json_decode($content, false);
	//var_dump(json_decode($content));

	//print_r($obj);
	//echo "".$obj[0]->STAFFID;



	//$reg_num=$obj[0]->STAFFID;
	$reg_num=str_pad($obj[0]->APPLY_ID, 3, '0', STR_PAD_LEFT);

	if($obj[0]->PREFIXNAME=="นาย"){
		$prename=1;
	}elseif($obj[0]->PREFIXNAME=="นาง"){
		$prename=2;
	}elseif($obj[0]->PREFIXNAME=="นางสาว"){
		$prename=3;
	}

	
	$staffname=$obj[0]->STAFFNAME;
	$staffsurname=$obj[0]->STAFFSURNAME;
	$fullname =$staffname." ".$staffsurname;
	$date =$obj[0]->BIRTHDATE;
	$marrystatus=$obj[0]->MARRYSTATUS;
	$homeadd=$obj[0]->HOMEADD;
	$districtname=$obj[0]->DISTRICTNAME;
	$cityname=$obj[0]->CITYNAME;
	$provincename=$obj[0]->PROVINCENAME;
	$zipcode=$obj[0]->ZIPCODE;
	$mobile=$obj[0]->MOBILE;
	$tel=$obj[0]->TEL;
	$workpositionname=$obj[0]->WORKPOSITIONNAME;
	$age=$obj[0]->AGE;
	$y=$obj[0]->Y;
	$m=$obj[0]->M;
	$d=$obj[0]->D;

	$cept=$obj[0]->CEPT;
	

	$loan=$obj[0]->LOAN;

	if($obj[0]->LOAN==""){
		$loan="-";
	}else{
		if($obj[0]->LOAN==0){
			$loan="กยศ.";
		}else{
			$loan="กรอ.";
		}
		
	}
	$dept=$obj[0]->DEPARTMENTNAME;
	$faculty=$obj[0]->FACULTYNAME;

	$loan_status=$obj[0]->LOANSTATUS;


	$chEdu = curl_init();

	// Set query data here with the URL

	curl_setopt($chEdu, CURLOPT_URL, $education_data_url . $qry_str); 
	curl_setopt($chEdu, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($chEdu, CURLOPT_TIMEOUT, 3);
	$contentEdu = curl_exec($chEdu);
	//curl_close($ch);
	//print $content;
	curl_close($chEdu);
	//echo $content;
	$objEdu = json_decode($contentEdu, true);

	foreach($objEdu as $key=>$value){
		
		$education[$key]=array("degree"=>$value["DEGREELEVELNAME"],"degree_name"=>$value["DEGREENAME"],"major"=>$value["MAJORNAME"]." (".$value["GPA"].")","institution"=>$value["UNIVERSITYNAME"],"education_year"=>$value["GADUATEYEAR"]);
	}

	//$education[0]=array("degree"=>"ปริญญาตรี","degree_name"=>"วิทยาศาสตรบัณฑิต","major"=>"วิทยาการคอมพิวเตอร์ (2.10)","institution"=>"มหาวิทยาลัยนเรศวร","education_year"=>"2525");
	//$education[1]=array("degree"=>"ปริญญาโท","degree_name"=>"วิทยาศาสตรมหาบัณฑิต","major"=>"วิทยาการคอมพิวเตอร์ (3.10)","institution"=>"มหาวิทยาลัยนเรศวร","education_year"=>"2530");

	$pdf=new FPDF('P' , 'mm' , 'A4' );
	$pdf->AddFont('THSarabun','','THSarabun.php');

	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวหนา  กำหนด ชื่อ เป็น THSarabun
	$pdf->AddFont('THSarabun','B','THSarabun Bold.php');

	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวหนา  กำหนด ชื่อ เป็น THSarabun
	$pdf->AddFont('THSarabun','I','THSarabun Italic.php');

	// เพิ่มฟอนต์ภาษาไทยเข้ามา ตัวหนา  กำหนด ชื่อ เป็น THSarabun
	$pdf->AddFont('THSarabun','BI','THSarabun Bold Italic.php');

	$pdf->AddPage();


	$pdf->SetFont('THSarabun','B',16);
	$pdf->Text(150 , 10 ,  iconv( 'UTF-8','cp874' , 'เลขประจำตัวสอบ    '.$reg_num));
	$pdf->Line(178,11,200,11);

	$pdf->SetFont('THSarabun','B',18);
	$pdf->SetXY(18,18);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(160,15,iconv( 'UTF-8','cp874' , 'ใบสมัครสอบคัดเลือกบุคคลเพื่อบรรจุและแต่งตั้งเข้าเป็นพนักงานมหาวิทยาลัย' ),0,0,'C',true);


	$pdf->SetFont('THSarabun','',12);
	$pdf->SetXY(176,14);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(25,28,iconv( 'UTF-8','cp874' , 'รูปถ่ายขนาด  1 นิ้ว' ),1,0,'C',true);

	$pdf->SetFont('THSarabun','',16);
	$pdf->Text(15 , 40 ,  iconv( 'UTF-8','cp874' , '1.   ชื่อ และนามสกุล (นาย,นาง,นางสาว)  '.$fullname));
	
	if($prename==1){
		//$pdf->Line(49,39,55,39); // 1
		$pdf->Line(56,39,61,39); // 2
		$pdf->Line(63,39,74,39); // 3
	}elseif($prename==2){
		$pdf->Line(49,39,55,39); // 1
		//$pdf->Line(56,39,61,39); // 2
		$pdf->Line(63,39,74,39); // 3
	}elseif($prename==3){
		$pdf->Line(49,39,55,39); // 1
		$pdf->Line(56,39,61,39); // 2
		//$pdf->Line(63,39,74,39); // 3
	}

	$pdf->Text(15 , 48 ,  iconv( 'UTF-8','cp874' , '2.   เกิดวันที่  '.$date));
	$pdf->Line(34,49,125,49);


	$pdf->Text(125 , 48 ,  iconv( 'UTF-8','cp874' , 'ปัจจุบันอายุ  '.$age));
	$pdf->Line(143,49,160,49);
	$pdf->Text(160 , 48 ,  iconv( 'UTF-8','cp874' , '(ปี)  '));
	$pdf->Line(75,41,175,41);



	$pdf->Text(15 , 56 ,  iconv( 'UTF-8','cp874' , '3.   สถานภาพ  '));
	$pdf->Text(50 , 56 ,  iconv( 'UTF-8','cp874' , 'โสด'));
	$pdf->Text(66 , 56 ,  iconv( 'UTF-8','cp874' , 'สมรส'));
	$pdf->Text(88 , 56 ,  iconv( 'UTF-8','cp874' , 'หย่าร้าง'));
	$pdf->Text(110 , 56 ,  iconv( 'UTF-8','cp874' , 'หม้าย'));

	if($marrystatus==0){
		 $pdf-> Image('images/check.png',42,52,5,5,'png');
		 $pdf-> Image('images/uncheck.png',58,52,6,6,'png');
		 $pdf-> Image('images/uncheck.png',78,52,6,6,'png');
		 $pdf-> Image('images/uncheck.png',102,52,6,6,'png');
	}elseif($marrystatus==1){
		 $pdf-> Image('images/uncheck.png',42,52,5,5,'png');
		 $pdf-> Image('images/check.png',58,52,5,5,'png');
		 $pdf-> Image('images/uncheck.png',78,52,6,6,'png');
		 $pdf-> Image('images/uncheck.png',102,52,6,6,'png');
	}elseif($marrystatus==2){
		 $pdf-> Image('images/uncheck.png',42,52,5,5,'png');
		 $pdf-> Image('images/uncheck.png',58,52,5,5,'png');
		 $pdf-> Image('images/check.png',78,52,6,6,'png');
		 $pdf-> Image('images/uncheck.png',102,52,6,6,'png');
		
	}elseif($marrystatus==3){
		 $pdf-> Image('images/uncheck.png',42,52,5,5,'png');
		 $pdf-> Image('images/uncheck.png',58,52,5,5,'png');
		 $pdf-> Image('images/uncheck.png',78,52,6,6,'png');
		 $pdf-> Image('images/check.png',102,52,6,6,'png');
		
	}
	



	$pdf->Text(15 , 64 ,  iconv( 'UTF-8','cp874' , '4.   สถานที่ติดต่อได้สะดวกรวดเร็ว  เลขที่      '.$homeadd));

	$pdf->Line(76,65,200,65); //full


	$pdf-> Image('images/nu_watermark_word.png',45,74,125,125,'png');

	$pdf->Text(15 , 72 ,  iconv( 'UTF-8','cp874' , '    ตำบล/แขวง  '.$districtname));
	$pdf->Line(40,73,90,73); 
	$pdf->Text(85 , 72 ,  iconv( 'UTF-8','cp874' , '    อำเภอ/เขต  '.$cityname));
	$pdf->Line(109,73,150,73); 
	$pdf->Text(144 , 72 ,  iconv( 'UTF-8','cp874' , '     จังหวัด  '.$provincename));
	$pdf->Line(161,73,200,73); 

	$pdf->Text(15 , 80 ,  iconv( 'UTF-8','cp874' , '    รหัสไปรษณีย์  '.$zipcode));
	$pdf->Line(41,81,70,81); 
	$pdf->Text(65 , 80 ,  iconv( 'UTF-8','cp874' , '     โทรศัพท์  '.$tel));
	$pdf->Line(85,81,130,81); 
	$pdf->Text(125 , 80 ,  iconv( 'UTF-8','cp874' , '     มือถือ  '.$mobile));
	$pdf->Line(140,81,200,81); 


	$pdf->Text(15 , 88 ,  iconv( 'UTF-8','cp874' , '5.   ชื่อประกาศนียบัตร'));
	$pdf->Text(60 , 88 ,  iconv( 'UTF-8','cp874' , 'สาขาหรือแผนก'));
	$pdf->Text(110 , 88 ,  iconv( 'UTF-8','cp874' , 'วิชาเอก'));
	$pdf->Text(145 , 88 ,  iconv( 'UTF-8','cp874' , 'สถานศึกษา'));
	$pdf->Text(175 , 88 ,  iconv( 'UTF-8','cp874' , 'สำเร็จการศึกษา'));

	$pdf->Text(20 , 96 ,  iconv( 'UTF-8','cp874' , ' หรือปริญญาที่ได้รับ'));
	$pdf->Text(180 , 96 ,  iconv( 'UTF-8','cp874' , ' เมื่อปี พ.ศ.'));

	$pdf->SetFont('THSarabun','',14);
	


	$pdf->SetFillColor($r,$g,$b);
	//$pdf->SetXY(20,98); 
/*	$pdf->Cell(35,8,iconv( 'UTF-8','cp874' , "ปริญญาตรี" ),'B',1,'L',false );

	$pdf->SetXY(58,98); 
	$pdf->Cell(30,8,iconv( 'UTF-8','cp874' , "วิทยาศาสตรบัณฑิต" ),'B',1,'L',false );

	$pdf->SetXY(95,98); 
	$pdf->Cell(40,8,iconv( 'UTF-8','cp874' , "วิทยาการคอมพิวเตอร์ (2.10)" ),'B',1,'L',false );

	$pdf->SetXY(139,98); 
	$pdf->Cell(30,8,iconv( 'UTF-8','cp874' , "มหาวิทยาลัยนเรศวร" ),'B',1,'L',false );

	$pdf->SetXY(180,98); 
	$pdf->Cell(15,8,iconv( 'UTF-8','cp874' , "2546" ),'B',1,'L',false );*/

	//$posItemList_y=106;
	$posItemList_y=98;
	foreach($education as $key=>$value){
		$pdf->SetXY(20,$posItemList_y); 
		$pdf->Cell(35,8,iconv( 'UTF-8','cp874' , $value["degree"] ),'B',1,'L',false );

		$pdf->SetXY(58,$posItemList_y); 
		$pdf->Cell(30,8,iconv( 'UTF-8','cp874' , $value["degree_name"] ),'B',1,'L',false );

		$pdf->SetXY(95,$posItemList_y); 
		$pdf->Cell(40,8,iconv( 'UTF-8','cp874' , $value["major"] ),'B',1,'L',false );

		$pdf->SetXY(139,$posItemList_y); 
		$pdf->Cell(30,8,iconv( 'UTF-8','cp874' , $value["institution"] ),'B',1,'L',false );

		$pdf->SetXY(180,$posItemList_y); 
		$pdf->Cell(15,8,iconv( 'UTF-8','cp874' , $value["education_year"] ),'B',1,'L',false );
		$posItemList_y +=8;
	}

	$posItemList_y +=8;
	$pdf->SetFont('THSarabun','',16);
	$pdf->Text(15 , $posItemList_y ,  iconv( 'UTF-8','cp874' , '6.   ปัจจุบันปฏิบัติงานในตำแหน่ง  '.$workpositionname));
	$pdf->Line(65,$posItemList_y+1,200,$posItemList_y+1); 

	$posItemList_y +=8;
	$pdf->Text(22 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'สังกัด ภาค/กอง  '.$dept));
	$pdf->Line(45,$posItemList_y+1,200,$posItemList_y+1); 
	$pdf->Text(160 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'โทรศัพท์ '.$tel));

	$posItemList_y +=8;
	$pdf->Text(22 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'สำนัก/คณะ  '.$faculty));
	$pdf->Line(40,$posItemList_y+1,140,$posItemList_y+1); 
	//$pdf->Text(22 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'โทรศัพท์'));
	//$pdf->Text(22 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'เป็นเวลา'));
	//$pdf->Line(35,$posItemList_y+1,60,$posItemList_y+1); 
	$pdf->Text(140 , $posItemList_y ,  iconv( 'UTF-8','cp874' , ' รวมอายุงาน'));

	$pdf->Text(160 , $posItemList_y ,  iconv( 'UTF-8','cp874' , $y.' ปี'));
	//$pdf->Line(63,$posItemList_y+1,80,$posItemList_y+1); 
	$pdf->Text(170 , $posItemList_y ,  iconv( 'UTF-8','cp874' , $m.' เดือน'));
	//$pdf->Line(89,$posItemList_y+1,109,$posItemList_y+1); 
	$pdf->Text(184 , $posItemList_y ,  iconv( 'UTF-8','cp874' , $d.' วัน'));
	$pdf->Line(160,$posItemList_y+1,165,$posItemList_y+1); 
	$pdf->Line(168,$posItemList_y+1,174,$posItemList_y+1); 
	$pdf->Line(181,$posItemList_y+1,190,$posItemList_y+1); 

	$posItemList_y +=8;
	$pdf->Text(15 , $posItemList_y ,  iconv( 'UTF-8','cp874' , '7.   ข้าพเจ้ามีคะแนนทดสอบภาษาอังกฤษ (CEPT) ในระดับ      '.$cept));
	$pdf->Line(103,$posItemList_y+1,140,$posItemList_y+1); 
	$pdf->Text(140 , $posItemList_y ,  iconv( 'UTF-8','cp874' , '(ถ้ามี)'));

	$posItemList_y +=8;
	$pdf->Text(15 , $posItemList_y ,  iconv( 'UTF-8','cp874' , '8.   การกู้ยืมเงินจากกองทุน กยศ. / กรอ. (กรณีเคยกู้ยืมกรุณาระบุกองทุน)   '.$loan));
	$pdf->Line(125,$posItemList_y+1,200,$posItemList_y+1); 

	$posItemList_y +=8;
	$pdf->Text(20 , $posItemList_y ,  iconv( 'UTF-8','cp874' , ' กรณีที่เคยกู้ยืมเงินจากกองทุน กยศ. / กรอ.'));
	$pdf->Text(100 , $posItemList_y ,  iconv( 'UTF-8','cp874' , ' อยู่ระหว่างผ่อนชำระ     '));
	$pdf->Text(150 , $posItemList_y ,  iconv( 'UTF-8','cp874' , ' ชำระครบจำนวนแล้ว     '));

	if($loan_status==""){
		$pdf-> Image('images/uncheck.png',90,$posItemList_y-4,6,6,'png');
		$pdf-> Image('images/uncheck.png',140,$posItemList_y-4,6,6,'png');
	}elseif($loan_status==0){
		 $pdf-> Image('images/check.png',90,$posItemList_y-4,5,5,'png');
		 $pdf-> Image('images/uncheck.png',140,$posItemList_y-4,6,6,'png');
	}elseif($loan_status==1){
		 $pdf-> Image('images/uncheck.png',90,$posItemList_y-4,6,6,'png');
		 $pdf-> Image('images/check.png',140,$posItemList_y-3,5,5,'png');
	}else{

	}

	$posItemList_y +=10;
	$pdf->Text(20 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'ข้าพเจ้าขอรับรองว่าข้อความข้างต้นนั้นเป็นความจริงทุกประการ'));

	$posItemList_y +=10;
	$pdf->Text(110 , $posItemList_y ,  iconv( 'UTF-8','cp874' , '(ลายเซ็น)……………………………………………………….ผู้สมัครสอบ'));
	$posItemList_y +=8;
	$pdf->Text(110 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'ยื่นใบสมัครวันที่'));
	$pdf->Line(134,$posItemList_y+1,141,$posItemList_y+1); 
 	$pdf->Text(142 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'เดือน'));
	$pdf->Line(150,$posItemList_y+1,170,$posItemList_y+1); 
	$pdf->Text(172 , $posItemList_y ,  iconv( 'UTF-8','cp874' , 'พ.ศ.'));
	$pdf->Line(178,$posItemList_y+1,200,$posItemList_y+1); 

	$posItemList_y +=4;
	$pdf->SetFont('THSarabun','B',15);
	$pdf->SetXY(10,$posItemList_y+2);
	$pdf->SetFillColor(255,255,255);
	$pdf->Cell(190,8,iconv( 'UTF-8','cp874' , 'เฉพาะเจ้าหน้าที่' ),1,0,'C',true);
	
	$posItemList_y +=8;
	$pdf->SetXY(10,$posItemList_y+2);
	$pdf->Cell(65,64,iconv( 'UTF-8','cp874' , "" ),'LBR',1,'L',false );

	
	$pdf->SetFont('THSarabun','',13);
	$pdf->Text(12 , $posItemList_y+8 ,  iconv( 'UTF-8','cp874' , '1.   ได้ตรวจรายละเอียดเกี่ยวกับใบสมัครสอบ'));
	$pdf->Text(12 , $posItemList_y+14 ,  iconv( 'UTF-8','cp874' , '     และคุณสมบัติของผู้สมัครคัดเลือกรายนี้'));
	$pdf->Text(12 , $posItemList_y+20 ,  iconv( 'UTF-8','cp874' , '     แล้ว เห็นว่าถูกต้อง มีสิทธิที่จะสมัครสอบ'));
	$pdf->Text(12 , $posItemList_y+26 ,  iconv( 'UTF-8','cp874' , '     คัดเลือกในครั้งนี้ได้'));
	$pdf->Text(30 , $posItemList_y+32 ,  iconv( 'UTF-8','cp874' , '     …………………………………'));
	$pdf->Text(30 , $posItemList_y+38 ,  iconv( 'UTF-8','cp874' , '       เจ้าหน้าที่รับสมัคร'));
	$pdf->Text(30 , $posItemList_y+44 ,  iconv( 'UTF-8','cp874' , '       ………/………/………'));

	$pdf->SetXY(75,$posItemList_y+2);
	$pdf->Cell(65,64,iconv( 'UTF-8','cp874' , "" ),'BR',1,'L',false );
	$pdf->SetFont('THSarabun','',13);
	$pdf->Text(80 , $posItemList_y+8 ,  iconv( 'UTF-8','cp874' , '2.   ได้ตรวจคุณสมบัติทั่วไปและคุณสมบัติเฉพาะ'));
	$pdf->Text(80 , $posItemList_y+14 ,  iconv( 'UTF-8','cp874' , '     ของผู้สมัครสอบคัดเลือกในใบสมัครแล้ว'));
	$pdf->Text(80 , $posItemList_y+20 ,  iconv( 'UTF-8','cp874' , '     ปรากฏว่า  “มีสิทธิสมัครสอบคัดเลือกได้”'));
	$pdf->Text(80 , $posItemList_y+26 ,  iconv( 'UTF-8','cp874' , '     มีปัญหาเรื่อง……………………………………………'));
	$pdf->Text(80 , $posItemList_y+32 ,  iconv( 'UTF-8','cp874' , '     ………………………………………………………………'));
	$pdf->Text(80 , $posItemList_y+38 ,  iconv( 'UTF-8','cp874' , '     ความเห็นเจ้าหน้าที่……………………………………'));
	$pdf->Text(80 , $posItemList_y+44 ,  iconv( 'UTF-8','cp874' , '     ………………………………………………………………'));
	$pdf->Text(96 , $posItemList_y+50 ,  iconv( 'UTF-8','cp874' , '     …………………………………'));
	$pdf->Text(96 , $posItemList_y+56 ,  iconv( 'UTF-8','cp874' , '       เจ้าหน้าที่รับสมัคร'));
	$pdf->Text(96 , $posItemList_y+62 ,  iconv( 'UTF-8','cp874' , '       ………/………/………'));


	$pdf->SetXY(130,$posItemList_y+2);
	$pdf->Cell(70,64,iconv( 'UTF-8','cp874' , "" ),'BR',1,'L',false );

	$pdf->SetFont('THSarabun','',13);
	$pdf->Text(142 , $posItemList_y+8 ,  iconv( 'UTF-8','cp874' , '3. ได้ตรวจสอบหลักฐานต่าง ๆ  ของผู้สมัครสอบ'));
	$pdf->Text(142 , $posItemList_y+14 ,  iconv( 'UTF-8','cp874' , ' คัดเลือกแล้ว ปรากฏว่ามีสิทธิเข้าสอบคัดเลือก'));
	$pdf->Text(142 , $posItemList_y+20 ,  iconv( 'UTF-8','cp874' , ' ยังขาดหลักฐาน……………………………………………'));
	$pdf->Text(142 , $posItemList_y+26 ,  iconv( 'UTF-8','cp874' , ' ……………………………………………………………………'));
	$pdf->Text(142 , $posItemList_y+32 ,  iconv( 'UTF-8','cp874' , ' ……………………………………………………………………'));
	$pdf->Text(142 , $posItemList_y+38 ,  iconv( 'UTF-8','cp874' , ' ความเห็นเจ้าหน้าที่………………………………………'));
	$pdf->Text(142 , $posItemList_y+44 ,  iconv( 'UTF-8','cp874' , ' …………………………………………………………………'));
	$pdf->Text(160 , $posItemList_y+50 ,  iconv( 'UTF-8','cp874' , '     …………………………………'));
	$pdf->Text(160 , $posItemList_y+56 ,  iconv( 'UTF-8','cp874' , '       เจ้าหน้าที่รับสมัคร'));
	$pdf->Text(160 , $posItemList_y+62 ,  iconv( 'UTF-8','cp874' , '       ………/………/………'));



	$pdf->Output();

?>
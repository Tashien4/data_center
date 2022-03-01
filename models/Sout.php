<?php

namespace app\models;

use Yii;
class Sout extends \yii\db\ActiveRecord
{


        public $kod_reg='2594';
        public $factrootns="urn://egisso-ru/msg/10.06.S/1.0.5";
        public $factpac="urn://egisso-ru/types/package-RAF/1.0.6";
        public $ns2=     "urn://egisso-ru/types/assignment-fact/1.0.6";
        public $af=     "urn://egisso-ru/types/assignment-fact/1.0.8";
        public $ns3=   "urn://egisso-ru/types/prsn-info/1.0.3"; 
        public $prsn=   "urn://egisso-ru/types/prsn-info/1.0.4";
        public $ns4=   "urn://x-artefacts-smev-gov-ru/supplementary/commons/1.0.1";
        public $smev=   "urn://x-artefacts-smev-gov-ru/supplementary/commons/1.0.1";
        public $ns5= "urn://egisso-ru/types/basic/1.0.4";
        public $egisso= "urn://egisso-ru/types/basic/1.0.8";
        public $lmszrootns="urn://egisso-ru/msg/10.05.S/1.0.4";
        public $lmsz="urn://egisso-ru/types/local-MSZ/1.0.4";
        public $lmszpac="urn://egisso-ru/types/package-LMSZ/1.0.4";
        public $xsi="http://www.w3.org/2001/XMLSchema-instance";
        
        public $expodom;
        public $exporoot;

//---------------------------------------
        public static function tableName()
        {
            return 'outfiles';
        }
//---------------------------------------
        public function rules()
        {
            return [
              [['name','exporoot','expodom'], 'string', 'max' => 10000],
                [['id'], 'integer'],
                [['date'], 'safe']   
            ];
        }
    //----------------------------------------------------------
    public function statist($per){
        $sql='select count(id) as al,sum(if(in_period>1,1,0)) as ot,sum(if(in_period<2,1,0)) as stay
        from expo
        where id_period='.$per.'
        group by id_period';
        $command=Yii::$app->db->createCommand($sql);
        $row= $command->queryOne();
return $row;
    }
   
//----------------------------------------------------------------
       public function clea_in_period($old=1) { //очистить перед прогоном все временные отметки (1)
        $ret=array();
            if ($old>0) {
            $sql=sprintf("
    
                UPDATE expo
                 SET expo.in_period=0
                WHERE expo.in_period=%s
            ;
        ",$old
        );                                
        $connection=Yii::$app->db; 
    //print $sql;
        $updatecommand=$connection->createCommand($sql);
        $ret=$updatecommand->execute(); 
    
        }
     return($ret);
    
    }
    //-------------------------------------------------
    public function find_coper($coper){
        $row=Yii::$app->db->createCommand('select name from _school where kod='.$coper)->queryOne();
        return $row['name'];
    }
    
    //-------------------------------------------------
     public function sout($per=0) { //выгрузка 
        $ret=array();
            if ($per>0) {
        $fname_templ=$this->kod_reg.'_10.06.S';
        $outDir = 'out/'. date('Y-m-d');
 
    $row=Yii::$app->db->createCommand('select max(id) as id from outfiles where 1')->queryOne();
    $ppp=$row['id']+1;


        $this->clea_in_period(1);
    //mkdir($outDir);
            if (!is_dir($outDir)) {          
                    mkdir($outDir,0777);                              
            } else Site::clearDir($outDir,$fname_templ.'_'.substr(1000+$ppp,1).'.xml');
        $sql=sprintf("	
                SELECT 	expo.id, expo.coper, 30 as autor
                    ,kk.id as kmsasgo_id
                    ,msp_asgo.id as mspa_id
                    ,expo.nach
                    ,if(expo.data_resh='0000-00-00',
                        if(msp_asgo.needneeds!='' or kk.needscriteria!='',
                            expo.rbegin,
                            if(isnull(ld.doks_dat) or ld.doks_dat='0000-00-00',
                                expo.rbegin,
                                ld.doks_dat
                            )
                        ),
                        expo.data_resh) as data_resh
                    ,expo.rbegin
                    ,expo.rend
                    ,msp_asgo.onmsz
                    ,if(msp_asgo.needneeds='',kk.needscriteria,msp_asgo.needneeds) as needs
                    ,msp_asgo.mspform
                    ,expo.lastedit
                    ,expo.id_period
                    ,0 as ogran_period
                    ,expo.id_fio
      
                    ,kk.proz778
                    ,kk.okei,
    
                    ld.doktype as lgdoktype,
                    ld.doks_ser as lg_ser,
                    ld.doks_nom as lg_nom, 
                    ld.doks_dat as lg_dat, 
                    trim(ld.doks_kem) as lg_kem
                    ,_doktypes.name as lg_viddok
                    ,kk.needmama
    
    
                FROM expo
                INNER JOIN fio ON fio.id=expo.id_fio
                INNER JOIN doks pd ON pd.id=expo.actu_pasp
                LEFT JOIN doks ld ON ld.id=expo.actu_lgdok
                LEFT JOIN _doktypes ON _doktypes.id=ld.doktype
                INNER JOIN kmsasgo kk ON kk.id=expo.id_kmsasgo
                INNER JOIN msp_asgo ON msp_asgo.id=kk.id_mspa
         
        
                WHERE expo.in_period=0 and expo.id_period=".$per."
                
                    AND (fio.snils>9000000)
                    AND (expo.nach>0 or msp_asgo.mebynolik>0)
                    AND (fio.fam!='') AND (fio.im!='') 
                    AND (not isnull(fio.rod) and (year(fio.rod) between 1910 and 2040)  and (month(fio.rod) between 1 and 12)  and (day(fio.rod) between 1 and 31)) 
    
                    AND !(day(kk.data_oko)>0)
                    AND (pd.doktype IN (0,21,12,13,19,901))
                    AND (day(pd.doks_dat)>0 or expo.id_kmsasgo=151)
                    AND ((day(ld.doks_dat)>0) OR (msp_asgo.needneeds!='') OR (kk.needscriteria!='')) 
    
           

                ORDER BY expo.id
                LIMIT 0,8999
    
                ;
        ");
        $command=Yii::$app->db->createCommand($sql); 
        $rows = $command->queryAll();
        $cou=0;
    //print_r($sql); 
        $dat=Date('Ymd');
        $filepp=$ppp; 
        $packetid=$dat.'-0000-0000-0000-000000'.substr(1000000+$filepp,1);
        $fname=$fname_templ.'_'.substr(1000+$filepp,1).'.xml';
 $packnode=$this->makeZag_sout($fname,$packetid);
        foreach($rows as $row) {
            if ($cou>1999) {
                
                    $this->expodom=Site::saveDom($this->expodom,$fname,$outDir);
                     $ret['links'][]=array('link'=>('/'.$outDir.'/'.$fname), 'fname'=>$fname, 'fsize'=>Site::get_filesize($outDir,$fname),'mszcount'=>$cou,'coper'=>$coper);
    //			}
    
                $filepp++;
                $fname=$fname_templ.'_'.substr(1000+$filepp,1).'.xml'; 
                $packetid=$dat.'-0000-0000-0000-000000'.substr(1000000+$filepp,1);
                $cou=0;
            }
           
            $packnode->appendChild($this->fillRec_sout($row));
                $cou++;
   // print_r($row);

    
        }
        
          $this->expodom=Site::saveDom($this->expodom,$fname,$outDir);
        $ret['links'][]=array('link'=>('/data_center/'.$outDir.'/'.$fname), 'fname'=>$fname, 'fsize'=>Site::get_filesize($outDir,$fname),'mszcount'=>$cou);
        unset($this->expodom);
        unset($this->exporoot);
        unset($k); 	
        unset($row);
        unset($rows);
    
        $sql=sprintf("	SELECT 	expo.id
                    ,kk.id as kmsasgo_id
                    ,msp_asgo.id as mspa_id,expo.coper
                    ,concat(fio.fam,' ',fio.im,' ',fio.ot) as fio
                    ,if(expo.id_kmsasgo=0,14,if( fio.snils<9000000, 1 ,
                        if (expo.nach=0 and msp_asgo.mebynolik=0, 2,
                            if(fio.fam='' or fio.im='', 3,
                                if(!(not isnull(fio.rod) and (year(fio.rod) between 1910 and 2040)  and (month(fio.rod) between 1 and 12)  and (day(fio.rod) between 1 and 31)), 4,
                                    IF (pd.doktype not IN (21,12,13,19,901), 5,
                                        IF( day(pd.doks_dat)=0, 6,
                                            IF ((day(ld.doks_dat)=0) and (msp_asgo.needneeds='') AND (kk.needscriteria=''), 7, 
                                                if(expo.actu_lgdok=0 and kk.needscriteria=0,13,99)
                                            )
                                        )
                                    )		
                                )
                            )
                        ) ))
                     as kodbrak
                    ,if(msp_asgo.needneeds='',kk.needscriteria,msp_asgo.needneeds) as needs
                    ,if(expo.coper>0,1,0) as is_obr
                    ,msp_asgo.mspform
                    ,msp_asgo.ogran_period
                    ,expo.id_fio
                    
    
                FROM expo
                INNER JOIN fio ON fio.id=expo.id_fio
                INNER JOIN doks pd ON pd.id=expo.actu_pasp
  
    
                LEFT JOIN doks ld ON ld.id=expo.actu_lgdok
                LEFT JOIN _doktypes ON _doktypes.id=ld.doktype
                INNER JOIN kmsasgo kk ON kk.id=expo.id_kmsasgo
                INNER JOIN msp_asgo ON msp_asgo.id=kk.id_mspa
    
                WHERE expo.in_period=0
                    AND expo.id_period=".$per." AND msp_asgo.id in (0,156,159,158)
                    AND !(day(kk.data_oko)>0)
    
                LIMIT 0,5999
    
                ;
        ");

        $command=Yii::$app->db->createCommand($sql); 
        $rows = $command->queryAll();
        $cou=0;
    
        foreach($rows as $row) {
    
            if ($row['kodbrak']!=99)
                $ret['brak'][$row['kodbrak']][]=$row;
        }

     return($ret);
    
    }
}
    //--------------------------------------------------------------
    //-------------------------------------------------
       private function makeZag_sout($fname,$packid=0) { 
        $this->expodom=Site::creaDom('utf-8');
        $_uniq="_uniq";
        $this->exporoot=$this->expodom->createElementNS($this->factrootns,'ns:data');
    
        $this->expodom->appendChild($this->exporoot);
            $this->exporoot->setAttributeNS($this->factpac,"ns1:$_uniq",''); //'xmlns',"urn://egisso-ru/msg/10.05.I/1.0.0");
    //		(автоматически добавляется описатель xmlns:pac="htt////")
            $this->exporoot->removeAttributeNS($this->factpac,$_uniq);
    //		(описатель останется xmlns:pac="htt////")
         //   $this->exporoot->setAttributeNS($this->lmsz,"lmsz:$_uniq",''); //'xmlns',"urn://egisso-ru/msg/10.05.I/1.0.0");
         //   $this->exporoot->removeAttributeNS($this->lmsz,$_uniq);
            $this->exporoot->setAttributeNS($this->ns2,"ns2:$_uniq",''); //'xmlns',"urn://egisso-ru/msg/10.05.I/1.0.0");
            $this->exporoot->removeAttributeNS($this->ns2,$_uniq);
            $this->exporoot->setAttributeNS($this->ns3,"ns3:$_uniq",''); //=   "urn://egisso-ru/types/prsn-info/1.0.1"; 
            $this->exporoot->removeAttributeNS($this->ns3,$_uniq);
            $this->exporoot->setAttributeNS($this->ns4,"ns4:$_uniq",''); //=   "urn://x-artefacts-smev-gov-ru/supplementary/commons/1.0.1";
            $this->exporoot->removeAttributeNS($this->ns4,$_uniq);
            $this->exporoot->setAttributeNS($this->ns5,"ns5:$_uniq",''); //=   "urn://x-artefacts-smev-gov-ru/supplementary/commons/1.0.1";
            $this->exporoot->removeAttributeNS($this->ns5,$_uniq);
    //	public $egisso= "urn://egisso-ru/types/basic/1.0.2";
    
    
    
        $levelpackage=$this->expodom->createElementNS($this->factpac,'package');
        $this->exporoot->appendChild($levelpackage);
            $levelpackageID=$this->expodom->createElementNS($this->factpac,'packageId');
            $levelpackage->appendChild($levelpackageID);
            $levelpackageID->appendChild($this->expodom->createTextNode($packid));
        $levelEls=$this->expodom->createElementNS($this->factpac,'elements');
            $levelpackage->appendChild($levelEls);
    
    
       return $levelEls;
    }
    //----------------------------------------------------------------
       private function fillRec_sout($row) {
    
        if ($row['ogran_period']>0) { // надо устанавливать начало и конец периода предоставления
            $aper=Site::fromperiod($row['id_period']);
            $rbegin=$aper['god'].'-'.substr(100+$aper['mes'],1).'-01';
            $rend=$aper['god'].'-'.substr(100+$aper['mes'],1).'-'.substr(100+Date('t',strtotime($rbegin)),1);
    //		if ($aper['mes']==2)
        } else {
            $rbegin=($row['rbegin']=='0000-00-00')?$row['lg_dat']:$row['rbegin'];
            $rend=$row['rend'];
        }

            $dataresh=($row['data_resh']=='0000-00-00')?Date('Y-m-d'):$row['data_resh'];

    
    
        $levelEl=$this->expodom->createElementNS($this->factpac,'fact');
    //		1.1 ID Идентификатор Uuid 1…1 Уникальный 128-битный номер, присвоенный факту назначения поставщиком информации (В случае ручного заполнения форм в кабинете поставщика идентификатор указывается автоматически)
     
    //		1.2 code Код Строка(11) 1…1 Указывается Код ОНМСЗ (Перечень кодов ОНМСЗ предоставляется поставщику информации в процессе регистрации ЕГИССО)
                $t=$this->expodom->createElement('ns2:oszCode');
                $t->appendChild($this->expodom->createTextNode($row['onmsz']));	
                $levelEl->appendChild($t);
    //		1.3 MSZreceiver Блок данных 1…1 Блок данных, содержащий сведения о человеке, которому назначена МСЗ(П).(Используется тип данных «Личные данные (prsn-info)». См. раздел 3.5)

                    $levelEl->appendChild($this->fillperson('ns2:mszReceiver',$this->info_idfio($row['id_fio'],$row['autor'],0,$row['id'])));
 
    
    //		1.4 reasonPersons 	Сведения о лицах, являющихся основанием для назначения меры Блок данных 0…1 Блок данных, содержащий сведения о человеке или группе людей, на основании которых у получателя возникло право получения указанной МСЗ(П). 
    //					Например, мера, которая назначается исходя из оценки совокупного дохода семьи – в данном случае в блоке перечисляются сведения о членах семьи.
    //				prsnInfo .... 
    //		1.4.1 prsnInfo Лицо, являющееся основанием для возникновения права на назначение МСЗ Блок данных 1…* Используется тип данных «Личные данные (prsn-info)». См. раздел 3.5
    
    
    
    //		1.5 LMSZID Идентификатор назначенной МСЗ uuid 1…1 Указывается идентификатор локальной МСЗ поставщика информации, указанный в реестре ЛМСЗ (Подраздел «3.1 Локальная МСЗ (localMSZ)» Пункт 1.1)
                $t=$this->expodom->createElement('ns2:lmszId'); $t->appendChild($this->expodom->createTextNode($this->make_uuid('MSP_ASGO',0,0,0,$row['mspa_id'])));	$levelEl->appendChild($t);
    //		1.6 categoryID Идентификатор локальной категории получателей МСЗ uuid 1…1 Указывается идентификатор локальной категории получателя, указанный в реестре ЛМСЗ (Подраздел «3.1 Локальная МСЗ (local-14№ П/П ЭЛЕМЕНТ ОПИСАНИЕ ТИП МН. КОММЕНТАРИЙ MSZ)» Пункт 1.7.5.1.1)
                $t=$this->expodom->createElement('ns2:categoryId'); $t->appendChild($this->expodom->createTextNode($this->make_uuid('KMSASGO',0,0,0,$row['kmsasgo_id'])));	$levelEl->appendChild($t);
    //		1.7 decisionDate Дата принятия решения о назначении Дата 1…1 Дата подписания в ОНМСЗ решения(приказа) о назначении меры.Например, 15 февраля 2017 принято решение о назначении гражданину ежемесячных выплат, начиная с 01 марта 2017. В данном случае указывается дата 15.02.2017
                $t=$this->expodom->createElement('ns2:decisionDate');$t->appendChild($this->expodom->createTextNode($dataresh)); $levelEl->appendChild($t); //($row['data_resh']=='0000-00-00')?$row['lg_dat']:$row['data_resh'])); $levelEl->appendChild($t);
    //		1.8 dateStart Дата начала действия назначения Дата 1…1 Дата начала действия меры, указанная в решении о назначении. Например, 15 февраля 2017 принято 
    //				решение о назначении гражданину ежемесячных выплат, начиная с 01 марта 2017. В данном случае указывается дата 01.03.2017
                $t=$this->expodom->createElement('ns2:dateStart');$t->appendChild($this->expodom->createTextNode($rbegin)); $levelEl->appendChild($t); 
    //		1.9 dateFinish Дата окончания действия назначения Дата 0…1 Если мера назначена пожизненно, то дата не указывается.
                if ($rend!='0000-00-00') {
                     $t=$this->expodom->createElement('ns2:dateFinish');$t->appendChild($this->expodom->createTextNode($rend)); $levelEl->appendChild($t);
                }
    //		1.10 documents Документы, дающие право на назначение МСЗ Блок данных 0…1 Блок данных, содержащий сведения о документах, предоставленных получателем, подтверждающих право на назначение МСЗ(П)
    //		if ($row['needlgdocs']>0) {
            if ($row['needs']=="") { // если ни в мере, ни в категории не прописаны критерии нуждаемости - тогда документы
                $t=$this->expodom->createElement('ns2:documents'); $levelEl->appendChild($t);
                    $tt=$this->expodom->createElement('ns2:document'); $t->appendChild($tt);
    //			1.10.1.1 title Наименование документа Строка(100) 1…1 
                    $ttt=$this->expodom->createElement('ns2:title');$ttt->appendChild($this->expodom->createTextNode($row['lg_viddok'])); $tt->appendChild($ttt);
    //			1.10.1.2 series Серия Строка(50) 0…1 
                    $ser=trim($row['lg_ser']);
    //				$aexp=explode($row['lg_ser']);
    //				foreach($aexp as $el)
    //					$ser.=$el;
    //				$ser=str_replace(" ","",$ser);
    //				$ser=str_replace("-","",$ser);
    //				$ser=str_replace("  ","",$ser);
                    $ttt=$this->expodom->createElement('ns2:series');$ttt->appendChild($this->expodom->createTextNode($ser)); $tt->appendChild($ttt);
    //			1.10.1.3 number Номер Строка(50) 0…1
                    $ttt=$this->expodom->createElement('ns2:number');$ttt->appendChild($this->expodom->createTextNode($row['lg_nom'])); $tt->appendChild($ttt);
    //			1.10.1.4 issue_date Дата выдачи Дата 1…1
                    $ttt=$this->expodom->createElement('ns2:issueDate');$ttt->appendChild($this->expodom->createTextNode($row['lg_dat'])); $tt->appendChild($ttt);
    //			1.10.1.5 authority Кем выдан Строка(100) 1…1
                    $ttt=$this->expodom->createElement('ns2:authority');$ttt->appendChild($this->expodom->createTextNode(trim($row['lg_kem']))); $tt->appendChild($ttt);
    //			1.10.1.6 start_date Дата начала действия документа Дата 1…1
                    $ttt=$this->expodom->createElement('ns2:startDate');$ttt->appendChild($this->expodom->createTextNode($row['lg_dat'])); $tt->appendChild($ttt);
    //			1.10.1.7 finish_date Дата окончания действия документа Дата 0…1
    //				$tt=$this->expodom->createElement('finish_date');$tt->appendChild($this->expodom->createTextNode($row['ouid'])); $t->appendChild($tt);
            }
    //		1.11 needsCriteria Сведения об использовании критериев нуждаемости при назначении МСЗ Блок данных 1…1 Блок содержит сведения об применении критериев нуждаемости при назначении меры, например в связи с "низкими доходами" или "трудной жизненной ситуацией".
                $t=$this->expodom->createElement('ns2:needsCriteria'); $levelEl->appendChild($t);
    //			1.11.1 usingSign Признак использования критериев нуждаемости при назначении МСЗ Логический 1…1 Значение «Истина» или «Ложь», соответственно при назначении с учетом критериев нуждаемости или без.
                    $tt=$this->expodom->createElement('ns2:usingSign');$tt->appendChild($this->expodom->createTextNode(($row['needs'])?'1':'0')); $t->appendChild($tt); // логический "Истина" "Ложь"
    //			1.11.2 criteria Перечень использованных критериев нуждаемости Строка(500) 0…1 Указывается описание НПА, в соответствии с которым определена нуждаемость 
                    if ($row['needs']) {
    //					$tt=$this->expodom->createElementNS($this->ns2,'criteria');$tt->appendChild($this->expodom->createTextNode('Приказ Министерства социальной политики Свердловской области №610 27.10.2012 г.')); $t->appendChild($tt);  // описание НПА определения нуждаемости
                        $tt=$this->expodom->createElement('ns2:criteria');$tt->appendChild($this->expodom->createTextNode($row['needs'])); $t->appendChild($tt);  // описание НПА определения нуждаемости
                    }
    
    //		1.12 assignment_info Сведения о назначении Блок данных 1…1 В данном блоке заполняются либо блок данных 1.12.1, либо блок данных 1.12.2, либо блок данных 1.12.3, либо блок данных 1.12.4 
    //			в зависимости от следующих форм предоставления МСЗ(П):денежная форма;натуральная форма;льгота;услуга;
                $t=$this->expodom->createElement('ns2:assignmentInfo'); $levelEl->appendChild($t);
    
                if ($row['mspform']==1)  {
                    if ($row['nach']>0) {	
    //			1.12.1 monetary_form Специфические сведения о фактах назначения по денежной форме предоставления Блок данных 0…1 Заполняется либо блок данных 1.12.1, либо блок данных 1.12.2, либо блок данных 1.12.3, либо блок данных 1.12.4
                    $tt=$this->expodom->createElement('ns2:monetaryForm'); $t->appendChild($tt); 
    //				1.12.1.1 amount Сумма Деньги 1…1 
                        $ttt=$this->expodom->createElement('ns2:amount');$ttt->appendChild($this->expodom->createTextNode($row['nach'])); $tt->appendChild($ttt); 
                    }
                } elseif ($row['mspform']==2)  {
    //			1.12.2 natural_form Специфические сведения о фактах назначения по натуральной форме предоставления Блок данных 0…1 Заполняется либо блок данных 1.12.1, либо блок данных 1.12.2, либо блок данных 1.12.3, либо блок данных 1.12.4
                    $tt=$this->expodom->createElement('ns2:naturalForm'); $t->appendChild($tt); 
    //				1.12.2.1 amount Количество Число 1…1 
                        $ttt=$this->expodom->createElement('ns2:amount');$ttt->appendChild($this->expodom->createTextNode('1')); $tt->appendChild($ttt); 
    //				1.12.2.2 measuryCode Код единицы измерения по справочнику ЕГИССО Строка(2) 0…1 Заполняется либо элемент 1.12.2.2, либо элемент 1.12.2.3 Указывается код позиции из справочника единиц измерения ЕГИССО
                        $ttt=$this->expodom->createElement('ns2:measuryCode');$ttt->appendChild($this->expodom->createTextNode('03')); $tt->appendChild($ttt); 
                        $ttt=$this->expodom->createElement('ns2:equivalentAmount');$ttt->appendChild($this->expodom->createTextNode($row['nach'])); $tt->appendChild($ttt); 
    //				}
    
    //					-----------------------
                } elseif (($row['mspform']==3) and ($row['nach']>0))  {
    //			1.12.3 exemptionForm Специфические сведения о фактах назначения по форме предоставления «льгота»Блок данных 0…1 Заполняется либо блок данных 1.12.1, либо блок данных 1.12.2, либо блок данных 1.12.3, либо блок данных 1.12.4
                    $tt=$this->expodom->createElement('ns2:exemptionForm'); $t->appendChild($tt); 
    //				1.12.3.1 amount Размер Число 1…1 
                    $ttt=$this->expodom->createElement('ns2:amount');$ttt->appendChild($this->expodom->createTextNode($row['proz778'])); $tt->appendChild($ttt); 
    //				1.12.3.2 measuryCode Код единицы измерения по справочнику ЕГИССО Строка(2) 0…1 Заполняется либо элемент 1.12.3.2, либо элемент 1.12.3.3 Указывается код позиции из справочника единиц измерения ЕГИССО
    //					$ttt=$this->expodom->createElementNS($this->af,'measuryCode');$ttt->appendChild($this->expodom->createTextNode('03')); $tt->appendChild($ttt); 
    //				1.12.3.3 OKEICode Код единицы измерения по ОКЕИ Строка(3) или Строка(4) 0…1 Заполняется либо элемент 1.12.3.2, либо элемент 1.12.3.3 Указывается код ОКЕИ, указанный в справочнике единиц измерения ЕГИССО
    //						01-рубль       ОКЕИ 383
    //						02-тыс.рубль   ОКЕИ 384
    //						03-штук        ОКЕИ 796
    //						04-упаковок    ОКЕИ 778
    //						05-процентов   ОКЕИ 744
    //						06-био.активн  ОКЕИ 9910
    //						07-кг          ОКЕИ 166
                        $ttt=$this->expodom->createElement('ns2:okeiCode');$ttt->appendChild($this->expodom->createTextNode('744')); $tt->appendChild($ttt); 
    //					$ttt=$this->expodom->createElementNS($this->af,'OKEICode');$ttt->appendChild($this->expodom->createTextNode(($row['okei']>0)?$row['okei']:'744')); $tt->appendChild($ttt); 
    //				1.12.3.4 monetization Признак монетизации Логический 1…1 Указывается значение «Истина» или «Ложь» в зависимости, от того может льгота быть монетизирована или нет.
                        $ttt=$this->expodom->createElement('ns2:monetization');$ttt->appendChild($this->expodom->createTextNode('0')); $tt->appendChild($ttt); 
    //				1.12.3.5 comment Комментарий Строка(200) 0…1 Дополнительный комментарий к назначенной МСЗ(П)
    //					$ttt=$this->expodom->createElementNS($this->af,'comment');$ttt->appendChild($this->expodom->createTextNode('Внеочередное получение путёвки')); $tt->appendChild($ttt); 
    //ёёёё				Это сумма-эквивалент
                    if ($row['okei']==383) {
                        $ttt=$this->expodom->createElement('ns2:equivalentAmount');$ttt->appendChild($this->expodom->createTextNode($row['nach'])); $tt->appendChild($ttt); 
                    }
    //					-----------------------
                } elseif ($row['mspform']==4)  {
    //			1.12.4 serviceForm Специфические сведения о фактах назначения по форме предоставления «услуга» Заполняется либо блок данных 1.12.1, либо блок данных 1.12.2, либо блок данных 1.12.3, либо блок данных 1.12.4
                    $tt=$this->expodom->createElement('serviceForm'); $t->appendChild($tt); 
    //				1.12.4.1 amount Количество Число 1…1
                        $ttt=$this->expodom->createElement('amount');$ttt->appendChild($this->expodom->createTextNode('1')); $tt->appendChild($ttt); 
    //				1.12.4.2 measuryCode Код единицы измерения по справочнику ЕГИССО Строка(2) 0…1 Указывается код позиции из справочника единиц измерения ЕГИССО
                        $ttt=$this->expodom->createElement('measuryCode');$ttt->appendChild($this->expodom->createTextNode('03')); $tt->appendChild($ttt); 
    //				1.12.4.3 OKEICode Код единицы измерения по ОКЕИ Строка(3) или Строка(4) 0…1 Заполняется либо элемент 1.12.4.2, либо элемент 1.12.4.3 
    //					Указывается код ОКЕИ, указанный в справочнике единиц измерения ЕГИССО
                    //	$ttt=$this->expodom->createElement('OKEICode');$ttt->appendChild($this->expodom->createTextNode('383')); $tt->appendChild($ttt); 
    //				1.12.4.4 content Содержание Строка(200) 0…1 Приводится описание состава услуги, предоставляемой получателю 
                        $ttt=$this->expodom->createElement('content');$ttt->appendChild($this->expodom->createTextNode('Предоставление земельного участка или социальной выплаты взамен земельного участка')); $tt->appendChild($ttt); 
    //				1.12.4.5 comment Комментарий Строка(200) 0…1 Дополнительный комментарий к назначенной МСЗ(П)
                        $ttt=$this->expodom->createElementNS($this->ns2,'equivalentAmount');$ttt->appendChild($this->expodom->createTextNode($row['nach'])); $tt->appendChild($ttt); 
    //					$ttt=$this->expodom->createElement('comment');$ttt->appendChild($this->expodom->createTextNode('ouid')); $tt->appendChild($ttt); 
                            }
    
    
          /*  if (isset($row['lastedit']) and $row['lastedit']>0) {
                $last=str_replace(' ','T',$row['lastedit']).'+05:00'; 
                $t=$this->expodom->createElementNS($this->factpac,'lastChanging'); $t->appendChild($this->expodom->createTextNode($last)); $levelEl->appendChild($t);
            }*/
            $t=$this->expodom->createElement('ns1:uuid'); 
            $t->appendChild($this->expodom->createTextNode($this->make_uuid('EXPO',0,0,0,$row['id'])));
            $levelEl->appendChild($t);
         //   if (isset($row['previd']) and $row['previd']>0) {
          //      $t=$this->expodom->createElementNS($this->factpac,'previosID'); $t->appendChild($this->expodom->createTextNode(($this->make_uuid('EXPO',0,0,0,$row['previd'])))); $levelEl->appendChild($t);
          //  }
            $this->otmetit($row['id'],1);
        return($levelEl);
    }
    //--------------------------------------------------------------
       private function make_uuid($r8='00000000',$r41=0,$r42=0,$r43=0,$r12='000000000000') { 
        if 	($r8=='MSP_ASGO') 	$r8='A0000001';
        elseif  ($r8=='KMSASGO' ) 	$r8='A0000002';
        elseif  ($r8=='EXPO')		$r8='A0000003';
        if ($r41==0) $r41='0000';
        if ($r42==0) $r42='0000';
        if ($r43==0) $r43='0000';
        if ($r12>0) $r12=substr(1000000000000+$r12,1);
        return ($r8.'-'.$r41.'-'.$r42.'-'.$r43.'-'.$r12);
    }
    //-------------------------------------------------
    
       private function fillperson($tag,$row) {
        $t=$this->expodom->createElement($tag); 
        if (isset($row['fam']) and ($row['fam'])) {
                    $tt=$this->expodom->createElement('ns3:SNILS'); $tt->appendChild($this->expodom->createTextNode($row['snils'])); $t->appendChild($tt);
                    $tt=$this->expodom->createElement('ns4:FamilyName'); $tt->appendChild($this->expodom->createTextNode($row['fam'])); $t->appendChild($tt);
                    $tt=$this->expodom->createElement('ns4:FirstName'); $tt->appendChild($this->expodom->createTextNode($row['im'])); $t->appendChild($tt);
                    if ($row['ot']) { $tt=$this->expodom->createElement('ns4:Patronymic'); $tt->appendChild($this->expodom->createTextNode($row['ot'])); $t->appendChild($tt);				}
    //				$tt=$this->expodom->createElement('MaidenFamilyName'); $tt->appendChild($this->expodom->createTextNode($row['im'])); $t->appendChild($tt);
    //				 	1.6 Фамилия при рождении Строка(100) 0…1
                    $tt=$this->expodom->createElement('ns3:Gender'); $tt->appendChild($this->expodom->createTextNode(($row['pol']==0)?'Male':'Female')); $t->appendChild($tt); 
    //					1.7 Пол Перечислимое 1…1 Male, Female20
                    $tt=$this->expodom->createElement('ns3:BirthDate'); 
    //					$date = date_create($row['rod']); $tt->appendChild($this->expodom->createTextNode(date_format($date,'d.m.Y')));	
                        $date = date_create($row['rod']); $tt->appendChild($this->expodom->createTextNode($row['rod']));	
                        $t->appendChild($tt);
    //					1.8 BirthPlace Место рождения Строка(500) 0…1
    //1.9 PhoneNumber Контактный телефон Строка(10) 0…1
    //1.10 Citizenship Гражданство, Код страны по ОКСМ (643) Строка(3) 0…1 Указывается Цифровой код из общероссийского классификатора стран мира (ОКСМ)
    //				$tt=$this->expodom->createElementNS($this->prsn,'IdentityDoc'); $t->appendChild($tt);
    if ( $row['autor']!=27 and $row['autor']!=28)  {
                    $tt=$this->expodom->createElement('ns3:IdentityDoc'); $t->appendChild($tt);
    // 					 Документ, удостоверяющий личность Блок данных 0…1 Один из блоков должен быть заполнен обязательно:

                    if ($row['doktype']==21) {
                        $ttt=$this->expodom->createElement('ns5:PassportRF'); $tt->appendChild($ttt); 
    //					1.11.1 PassportRF Данные о паспорте гражданина РФ Блок данных 0…1 
                            $ser=trim($row['pasp_ser']);
                            $ser=str_replace("  ","",$ser);
                            $ser=str_replace(" ","",$ser);
                            if (is_numeric(substr($ser,0,1))) //если не совсем старый паспорт
                                $ser=str_replace("-","",$ser);
    
    
                            $tttt=$this->expodom->createElement('ns5:Series');
                            $tttt->appendChild($this->expodom->createTextNode($ser)); 
                            $ttt->appendChild($tttt); 
    // 						1.11.1.1 Series Серия Строка(4) 1…1
                            $nom=substr((1000000+trim($row['pasp_nom'])),1);						
                            $tttt=$this->expodom->createElement('ns5:Number');$tttt->appendChild($this->expodom->createTextNode($nom)); $ttt->appendChild($tttt); 
    // 						1.11.1.2 Number Номер Строка(6) 1…1
                            $tttt=$this->expodom->createElement('ns5:issueDate');$tttt->appendChild($this->expodom->createTextNode($row['pasp_dat'])); $ttt->appendChild($tttt); 
    // 						1.11.1.3 IssueDate Дата выдачи Дата 1…1
                            $kem=trim($row['pasp_kem']);
                            $kem=str_replace("№","",$kem);
                            $kem=str_replace("/","",$kem);
                            $kem=str_replace('"',"",$kem);
                            $kem=str_replace('(',"",$kem);
                            $kem=str_replace(')',"",$kem);
                            $kem=str_replace("'","",$kem);
    
                            $tttt=$this->expodom->createElement('ns5:Issuer');$tttt->appendChild($this->expodom->createTextNode($kem)); $ttt->appendChild($tttt); 
    // 						1.11.1.4 Issuer Кем выдан Строка(200) 1…1
                    } elseif ($row['doktype']==13) { 
                        $ttt=$this->expodom->createElementNS('ns5:ForeignPassport'); $tt->appendChild($ttt); 
    //					1.11.2 ForeignPassport Данные о паспорте иностранного гражданина Блок данных 0…1 
                            $ser=trim($row['pasp_ser']);
                            $ser=str_replace("  ","",$ser);
                            $ser=str_replace(" ","",$ser);
    
                            $kem=trim($row['pasp_kem']);
    //						$kem=str_replace("№","",$kem);
    //						$kem=str_replace("/","",$kem);
    
    
                            $tttt=$this->expodom->createElement('ns5:Series');$tttt->appendChild($this->expodom->createTextNode($ser)); $ttt->appendChild($tttt); 
                            $nom=trim($row['pasp_nom']);						
                            $tttt=$this->expodom->createElement('ns5:Number');$tttt->appendChild($this->expodom->createTextNode($nom)); $ttt->appendChild($tttt); 
                            $tttt=$this->expodom->createElement('ns5:issueDate');$tttt->appendChild($this->expodom->createTextNode($row['pasp_dat'])); $ttt->appendChild($tttt); 
                            $tttt=$this->expodom->createElement('ns5:Issuer');$tttt->appendChild($this->expodom->createTextNode($kem)); $ttt->appendChild($tttt); 
    
                    } elseif ($row['doktype']==901) { 
                        $ttt=$this->expodom->createElement('ns5:BirthCertificate'); $tt->appendChild($ttt); 
    //					1.11.5 BirthCertificate Данные о свидетельстве о рождении Блок данных 0…1 
                            $ser=trim($row['pasp_ser']);
                            $ser=str_replace("  ","",$ser);
                            $ser=str_replace(" ","",$ser);
    
                            $kem=trim($row['pasp_kem']);
                            $kem=str_replace("№","",$kem);
                            $kem=str_replace("/","",$kem);
    
                            $tttt=$this->expodom->createElement('ns5:Series');$tttt->appendChild($this->expodom->createTextNode($ser)); $ttt->appendChild($tttt); 
                            $nom=substr((1000000+trim($row['pasp_nom'])),1);						
                            $tttt=$this->expodom->createElement('ns5:Number');$tttt->appendChild($this->expodom->createTextNode($nom)); $ttt->appendChild($tttt); 
                            $tttt=$this->expodom->createElement('ns5:issueDate');$tttt->appendChild($this->expodom->createTextNode($row['pasp_dat'])); $ttt->appendChild($tttt); 
                            $tttt=$this->expodom->createElement('ns5:Issuer');$tttt->appendChild($this->expodom->createTextNode($kem)); $ttt->appendChild($tttt); 
    //					$ttt=$this->expodom->createElementNS($this->smev,'ForeignPassport'); $tt->appendChild($ttt); 
    //					-----------------------------------------------------------------------------
                    }else {
                        $ttt=$this->expodom->createElement('ns4:OtherDocument'); $tt->appendChild($ttt); 
    //					1.11.8 Данные об ином документе, удостоверяющем личность Блок данных 0…1 
                            $ser=trim($row['pasp_ser']);
                            $ser=str_replace("  ","",$ser);
                            $ser=str_replace(" ","",$ser);
    
                            $kem=trim($row['pasp_kem']);
    //						$kem=str_replace("№","",$kem);
    //						$kem=str_replace("/","",$kem);
    
    
                            $tttt=$this->expodom->createElement('ns4:Series');$tttt->appendChild($this->expodom->createTextNode($ser)); $ttt->appendChild($tttt); 
                            $nom=trim($row['pasp_nom']);						
                            $tttt=$this->expodom->createElement('ns4:Number');$tttt->appendChild($this->expodom->createTextNode($nom)); $ttt->appendChild($tttt); 
                            $tttt=$this->expodom->createElement('ns4:issueDate');$tttt->appendChild($this->expodom->createTextNode($row['pasp_dat'])); $ttt->appendChild($tttt); 
                            $tttt=$this->expodom->createElement('ns4:Issuer');$tttt->appendChild($this->expodom->createTextNode($kem)); $ttt->appendChild($tttt); 
                                   }
   
        } // ---------------------autor=30 
        };// else '-------------------АХТУНГ!!!---------(снилс или номер уд. не в ранге)----------'.print_r($row);
        return ($t);
    }
    //--------------------------------------------
    static function info_idfio($id_fio=0,$autor,$mebewithoutsnils=0,$id=0) {
        $ret=array();
        if ($id_fio>0 and $id>0) {
    //		$aper=Site::fromperiod($per);
            $connection=Yii::$app->db; 
            $sql=sprintf("	
                SELECT 	fio.id
                    ,fio.snils,fio.fam,fio.im,fio.ot,fio.rod,fio.pol
                    ,pd.doktype,pd.doks_ser as pasp_ser,pd.doks_nom as pasp_nom, pd.doks_dat as pasp_dat, 
                    if(pd.doktype=21,concat(trim(pd.doks_kem_kod),' ',trim(pd.doks_kem)),trim(pd.doks_kem)) as pasp_kem
                    ,_doktypes.name as lg_viddok,
    
                    ld.doktype as lgdoktype,
                    ld.doks_ser as lg_ser,
                    ld.doks_nom as lg_nom, 
                    ld.doks_dat as lg_dat, 
                    trim(ld.doks_kem) as lg_kem
    
                FROm fio 
                inner join (select actu_pasp,actu_lgdok,id_fio from expo where id=%s) as expo on expo.id_fio=fio.id
                INNER JOIN doks pd ON pd.id=expo.actu_pasp
                LEFT JOIN doks ld ON ld.id=expo.actu_lgdok
                LEFT JOIN _doktypes ON _doktypes.id=ld.doktype
                
                WHERE fio.id=%s 
                    AND (day(rod)>0) 
                    AND (fam!='') AND (im!='') 
                                    %s				
                    AND (pd.doktype IN (0,21,12,13,19,901))
                LIMIT 1
    
                ;
            ",$id,$id_fio,(($mebewithoutsnils==0)?' AND (fio.snils>9000000) ':'')
            );
    //print $sql;                                
            $command=Yii::$app->db->createCommand($sql); 
            $ret=$command->queryOne();
            $ret['autor']=$autor; // для 30 автора - не выгружать осн.док	
        }
        return $ret;
    }
    //-------------------------------------------------
       public function otmetit($id=0,$kak=1) { //поставить предварительную отметку о выгрузке
        $ret=array();
            if ($id>0) {
            $sql=sprintf("
    
                UPDATE expo
                 SET expo.in_period=%s
                WHERE expo.id=%s
            ;
        ",$kak,$id
        );                                
        $connection=Yii::$app->db; 
    //print $sql;
        $updatecommand=$connection->createCommand($sql);
        $ret=$updatecommand->execute(); 
    
        }
     return($ret);
    
    }
    //------------------------------------
    
    
    }
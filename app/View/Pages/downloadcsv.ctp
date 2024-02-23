<?php
ini_set('default_charset', 'UTF-8');
$this->Csv->setFilename($search.'-ExportCSV'. time());

$header = array(
    'KEYWORDS', 'ID', 'URL', 'TITLE', 'UPLOADER', 'DATE', 'SNIPPET'
);

if(!empty($results)){
    if($search == 'Youtube'){
        foreach ($header as $val) {
            $this->Csv->addField($val);
        }
        $this->Csv->endRow();
        ini_set('default_charset', 'UTF-8');
        foreach ($results as $key => $value) {
            $this->Csv->addField(html_entity_decode($value['text']));
            if(!empty($value['content'])){
                $this->Csv->addField(html_entity_decode($value['content']['id']));
                $this->Csv->addField(html_entity_decode($value['content']['url']));
                $this->Csv->addField(html_entity_decode($value['content']['title']));
                $this->Csv->addField(html_entity_decode($value['content']['uploader']));
                $this->Csv->addField(html_entity_decode($value['content']['date']));
                $this->Csv->addField(html_entity_decode($value['content']['snippet']));
            }
            $this->Csv->endRow();
        }
    }else{
        foreach ($results as $key => $value) {
            if(!empty($value['text'])){
                $this->Csv->addField(html_entity_decode($value['text']));
                $this->Csv->addField(html_entity_decode(strip_tags($value['content'])));
                $this->Csv->endRow();
            }
        }
    }
}

echo $this->Csv->render(true, "UTF-8", 'auto');
exit;
<?php
/**
 * This is template for EAnnotationUtility
 * @see AnnotationUtility::generateNetbeansHelpers()
 */
?>
tag.<?=$data->i?>.documentation=<?= str_replace("\n", '\r\n', str_replace("\r", "\n", str_replace("\r\n", "\n", $this->render('netbeansAnnotationsDescription', ['data' => $data], true)))) . "\r\n";?>
tag.<?=$data->i?>.insertTemplate=<?= str_replace("\n", "", str_replace("\r", "", $data->insertTemplate)) . "\r\n"?>
tag.<?=$data->i?>.name=<?= str_replace("\n", "", str_replace("\r", "", $data->name)) . "\r\n";?>
tag.<?=$data->i?>.types=<?= implode(',', $data->targets) . "\r\n";?>
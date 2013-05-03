<?php


namespace Components;


  /**
   * Media_Scriptlet_Bla
   *
   * @package net.evalcode.components
   * @subpackage media.scriptlet
   *
   * @author evalcode.net
   */
  class Media_Scriptlet_Bla extends Http_Scriptlet
  {
    // OVERRIDES/IMPLEMENTS
    public function post()
    {

      $empty=new I18n_Locale();
      print_r(I18n_Locale::de_DE()->serializeJson());
      // print_r(unserialize(serialize(I18n_Locale::de_DE()))->getCountryTitle());

      return;

      profile_begin();

      $time=time();
      $date=Date::fromUnixTimestamp($time);

      print_r("$time\n");

      profile('reset');
      print_r(serialize($date)."\n");
      print_r(unserialize(serialize($date))->toUnixTimestamp()."\n");
      profile('internal');

      print_r($date->serialize()."\n");
      print_r($date->unserialize($date->serialize())->toUnixTimestamp()."\n");
      profile('php');

      print_r($date->serializeJson()."\n");
      print_r($date->unserializeJson($date->serializeJson())->toUnixTimestamp()."\n");
      profile('json');

      print_r(serialize($date)."\n");
      print_r(unserialize(serialize($date))->toUnixTimestamp()."\n");
      profile('internal');

      print_r($date->serialize()."\n");
      print_r($date->unserialize($date->serialize())->toUnixTimestamp()."\n");
      profile('php');

      print_r($date->serializeJson()."\n");
      print_r($date->unserializeJson($date->serializeJson())->toUnixTimestamp()."\n");
      profile('json');

      $result=profile_end();

      print_r($result->splitTimeTable());
    }

    public function get()
    {
      return $this->post();
    }
    //--------------------------------------------------------------------------
  }
?>

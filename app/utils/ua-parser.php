<?php

namespace Sakura\Utils;

use WhichBrowser\Parser;

class UaParser
{
  private $result;

  public function __construct(string $ua)
  {
    $this->result = new Parser($ua);
  }

  public function get_browser_name()
  {
    return $this->result->browser->getName();
  }

  public function get_browser_version()
  {
    return $this->result->browser->getVersion();
  }

  public function get_engine_name()
  {
    return $this->result->engine->getName();
  }

  public function get_engine_version()
  {
    return $this->result->engine->getVersion();
  }

  public function get_os_name()
  {
    return $this->result->os->getName();
  }

  public function get_os_version()
  {
    return $this->result->os->getVersion();
  }

  public function get_manufacturer()
  {
    return $this->result->device->getManufacturer();
  }

  public function get_model()
  {
    return $this->result->device->getModel();
  }

  public function get_device_type()
  {
    // TODO: does this need any exception handling?
    return $this->result->device->type;
  }

  public function get_public_display_content()
  {
    return [
      'os_name' => $this->get_os_name(),
      'os_version' => $this->get_os_version(),
      'browser_name' => $this->get_browser_name(),
      'browser_version' => $this->get_browser_version(),
      'engine_name' => $this->get_engine_name(),
      'engine_version' => $this->get_engine_version(),
      'manufacturer' => $this->get_manufacturer(),
      'model' => $this->get_model(),
      'device_type' => $this->get_device_type(),
    ];
  }
}

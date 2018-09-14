<?php
/* author: Ponomarev Denis <ponomarev@gmail.com> */

namespace dface\container;

use Interop\Container\Exception\NotFoundException as NFE;

class NotFoundException extends ContainerException implements NFE
{

}

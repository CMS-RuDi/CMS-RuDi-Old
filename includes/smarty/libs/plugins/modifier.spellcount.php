<?php

function smarty_modifier_spellcount($num, $one, $two, $many) {
    cmsCore::spellCount($num, $one, $two, $many);
}
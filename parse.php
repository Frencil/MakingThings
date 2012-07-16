<?php

/**
 * Initialize global vars
 */
$nodes = array();
$links = array();

$include_type = array('tools'     => 1,
                      'materials' => 1,
                      'processes' => 1);


/**
 * Parse CSV
 */
$raw = file_get_contents('MakingThings_Alpha.csv');
$lines = explode("\n",$raw);

// Loop through line-by-line
for ($l = 1; $l < count($lines); $l++){

  $terms = explode(",",$lines[$l]);

  $tool_idx     = 0;
  $material_idx = 0;
  $process_idx  = 0;

  if ($include_type['tools']) {
    $tool     = ucwords(strtolower(trim($terms[0])));
    $tool_idx = getNodeIdx('tools', $tool);
  }
  if ($include_type['materials']) {
    $material     = ucwords(strtolower(trim($terms[1])));
    $material_idx = getNodeIdx('materials', $material);
  }
  if ($include_type['processes']) {
    $process     = ucwords(strtolower(trim($terms[2])));
    $process_idx = getNodeIdx('processes', $process);
  }

  // Create links
  if ($tool_idx && $material_idx)
    createLink($tool_idx, $material_idx);

  if ($tool_idx && $process_idx)
    createLink($tool_idx, $process_idx);

  if ($material_idx && $process_idx)
    createLink($material_idx, $process_idx);

}


/**
 * Generate and print JSON
 */
for ($l = 0; $l < count($links); $l++){
  $links[$l]['value'] = 1;
}

$json = array('nodes' => $nodes,
	      'links' => $links);

echo json_encode($json);

exit;




/*******************************************************************
 * Helper Functions
 */
function getNodeIdx($nodeType, $nodeValue){

  global $nodes;

  if (!$nodeValue)
    return false;

  $nodeObject = array('name'  => $nodeValue,
                      'group' => $nodeType);

  $node_idx = array_search($nodeObject,$nodes);
  if ($node_idx === false){
    $node_idx = count($nodes);
    $nodes[]  = $nodeObject;
  }

  return $node_idx;

}

function createLink($linkSource, $linkTarget){
  global $links;
  $link = array('source' => $linkSource,
                'target' => $linkTarget);
  $link_idx = array_search($link, $links);
  if ($link_idx === false)
    $links[] = $link;
}

?>
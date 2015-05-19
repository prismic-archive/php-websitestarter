<?php

  $sidebarTextColor = $skin->getColor('skin.sidebar-text-color');

  $sidebarBackgroundColor = $skin->getColor('skin.sidebar-background-color');

?>

#right-panel {

  <?= $sidebarTextColor ? 'color:'.$sidebarTextColor->asText() : ''; ?>;

  <?= $sidebarBackgroundColor ? 'background-color:'.$sidebarBackgroundColor->asText() : ''; ?>;
}

#right-panel .search {

  <?= $sidebarBackgroundColor ? 'background-color:'.$sidebarBackgroundColor->asText() : ''; ?>;
}

#right-panel .search input {

  <?= $sidebarBackgroundColor ? 'background-color:'.$sidebarBackgroundColor->asText() : ''; ?>;
}

#right-panel .sidebar-section h3, #right-panel .sidebar-section li a, #right-panel h3 a {

  <?= $sidebarTextColor ? 'color:'.$sidebarTextColor->asText() : ''; ?>;
}

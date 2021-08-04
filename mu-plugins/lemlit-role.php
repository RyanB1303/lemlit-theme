<?php

/**
 * Plugin untuk membuat post proposal.
 *
 * @package GeneratePress
 */
class lemlit_role_create
{
  public function __construct()
  {
    add_action('muplugins_loaded', [$this, 'create_role_peneliti']);
    add_action('muplugins_loaded', [$this, 'create_role_drf']);
    add_action('muplugins_loaded', [$this, 'create_role_reviewer']);
    add_action('muplugins_loaded', [$this, 'create_role_dekan']);
    add_action('muplugins_loaded', [$this, 'create_role_jurusan']);
    add_action('muplugins_loaded', [$this, 'create_role_lemlit']);
  }

  public $proposal_cap = array(
    'edit_proposals' => true,
    'edit_publish_proposals' => true,
    'delete_proposals' => true,
    'edit_private_proposals' => true,
    'read_private_proposals' => true,
    'publish_proposals' => true,
    'read_proposals' => true,
  );

  function create_role_peneliti()

  {
    $author_role_set = get_role('author')->capabilities;
    $my_cap = array_merge($author_role_set, $this->proposal_cap);
    add_role('peneliti', 'Peneliti', $my_cap);
    $peneliti = get_role('peneliti');
    $peneliti->remove_cap('publish_proposals');
  }
  function create_role_reviewer()
  {
    $editor_role_set = get_role('editor')->capabilities;
    $my_cap = array_merge($editor_role_set, $this->proposal_cap);
    add_role('reviewer', 'Reviewer', $my_cap);
  }
  function create_role_drf()
  {
    $editor_role_set = get_role('editor')->capabilities;
    $my_cap = array_merge($editor_role_set, $this->proposal_cap);
    add_role('drf', 'DRF', $my_cap);
  }

  function create_role_lemlit()
  {
    $editor_role_set = get_role('editor')->capabilities;
    $my_cap = array_merge($editor_role_set, $this->proposal_cap);
    add_role('lemlit', 'Lemlit', $my_cap);
  }
  function create_role_dekan()
  {
    $contributor_role_set = get_role('contributor')->capabilities;
    $my_cap = array_merge($contributor_role_set, $this->proposal_cap);
    add_role('dekan', 'Dekan', $my_cap);
  }
  function create_role_jurusan()
  {
    $contributor_role_set = get_role('contributor')->capabilities;
    $my_cap = array_merge($contributor_role_set, $this->proposal_cap);
    add_role('jurusan', 'Jurusan', $my_cap);
  }
}

new lemlit_role_create();

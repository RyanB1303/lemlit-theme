<?php

/**
 * Plugin untuk membuat post proposal.
 *
 * @package GeneratePress
 */
class proposal_post_editor
{
  public function __construct()
  {
    add_action('init', [$this, 'create_role_peneliti']);
    add_action('init', [$this, 'create_role_drf']);
    add_action('init', [$this, 'create_role_reviewer']);
    add_action('init', [$this, 'create_role_dekan']);
    add_action('init', [$this, 'create_role_jurusan']);
    add_action('init', [$this, 'create_role_lemlit']);
  }
  protected $editor_role_set = get_role('editor')->capabilities;
  protected $author_role_set = get_role('author')->capabilities;
  protected $contributor_role_set = get_role('contributor')->capabilities;
  protected $proposal_cap = array(
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
    $my_cap = array_merge($this->author_role_set, $this->proposal_cap);
    add_role('peneliti', 'Peneliti', $my_cap);
    $peneliti = get_role('peneliti');
    $peneliti->remove_role('publish_proposals');
  }

  function create_role_drf()
  {
    $my_cap = array_merge($this->editor_role_set, $this->proposal_cap);
    add_role('drf', 'DRF', $my_cap);
  }
  function create_role_reviewer()
  {
    $my_cap = array_merge($this->editor_role_set, $this->proposal_cap);
    add_role('reviewer', 'Reviewer', $my_cap);
  }
  function create_role_dekan()
  {
    $my_cap = array_merge($this->contributor_role_set, $this->proposal_cap);
    add_role('dekan', 'Dekan', $my_cap);
  }
  function create_role_jurusan()
  {
    $my_cap = array_merge($this->contributor_role_set, $this->proposal_cap);
    add_role('jurusan', 'Jurusan', $my_cap);
  }
  function create_role_lemlit()
  {
    $my_cap = array_merge($this->editor_role_set, $this->proposal_cap);
    add_role('lemlit', 'Lemlit', $my_cap);
  }
}

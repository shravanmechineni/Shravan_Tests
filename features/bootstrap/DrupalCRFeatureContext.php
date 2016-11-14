<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class DrupalCRFeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * @Then I should see the correct sitemap elements
   * @And I should see the correct sitemap elements
   */
  public function iShouldSeeTheCorrectSitemapElements()
  {
    // Grab sitemap.xml page contents and parse it as XML using SimpleXML library
    $sitemap_contents = $this->getSession()->getDriver()->getContent();
    try {
      $xml = new SimpleXMLElement($sitemap_contents);
    } catch(Exception $e) {
      throw new Exception('Unable to read sitemap xml content - '.$e->getMessage());
    }

    // check if <url> nodes exist
    if (!($xml->count() > 0 && isset($xml->url))) {
      throw new InvalidArgumentException('No urlset found');
    }
  }

  /**
   * @Then I should see :url_path as a sitemap url
   * @And I should see :url_path as a sitemap url
   */
  public function iShouldSeeAsASitemapUrl($url_path)
  {
    // Grab sitemap.xml page contents and parse it as XML using SimpleXML library
    $sitemap_contents = $this->getSession()->getDriver()->getContent();
    try {
      $xml = new SimpleXMLElement($sitemap_contents);
    } catch(Exception $e) {
      throw new Exception('Unable to read sitemap xml content - '.$e->getMessage());
    }

    // Parse through each <url> node and check if url paths provided exist or not
    $path_found = false;
    foreach ($xml->children() as $xml_node) {
      if ( strpos($xml_node->loc, $url_path) ) {
        $path_found = true;
      }
    }

    // If no match found then throw exception
    if (!$path_found) {
      throw new InvalidArgumentException('Url not found');
    }
  }

  /**
   * @Given /^(?:|I )wait for AJAX loading to finish$/
   *
   * Wait for the jQuery AJAX loading to finish. ONLY USE FOR DEBUGGING!
   */
  public function iWaitForAJAX() {
    $this->getSession()->wait(5000, 'jQuery.active === 0');
  }

  /**
   * Creates a node that has paragraphs provided in a table.
   *
   * @Given I am viewing a/an :type( content) with :title( title) and :img( image) and :body( body) and with the following paragraphs:
   */
  public function assertParagraphs($type, $title, $image, $body, TableNode $paragraphs) {
    // First, create a landing page node.
    $node = (object) array(
      'title' => $title,
      'type' => $type,
      'uid' => 1,
    );
    $node = $this->nodeCreate($node);

    $paragraph_items = array();

    // Create paragraphs
    foreach ($paragraphs->getHash() as $paragraph) {
      $paragraph_item = $this->createParagraphItem($paragraph);
      $paragraph_items[] = [
        'target_id' => $paragraph_item->id(),
        'target_revision_id' => $paragraph_item->getRevisionId(),
      ];
    }

    // Add all the data to the node
    $node_loaded = \Drupal\node\Entity\Node::load($node->nid);
    $node_loaded->field_landing_image = $this->expandImage($image);
    $node_loaded->body = [
      'value' => $body,
      'format' => 'full_html',
    ];
    $node_loaded->field_paragraphs = $paragraph_items;
    $node_loaded->save();

    // Set internal page on the new landing page.
    $this->getSession()->visit($this->locatePath('/node/' . $node->nid));
  }

  /**
   * Helper function to create our different paragraph types.
   *
   * @param  [type] $paragraph [description]
   * @return [type]            [description]
   */
  private function createParagraphItem($paragraph) {
    // Default data for all paragraph types
    $data = [
      'type' => $paragraph['type'],
    ];

    // Every paragraph type might add specific data
    switch ($paragraph['type']) {
      case 'cr_rich_text_paragraph':
        $data['field_body'] = [
          'value' => $paragraph['body'],
          'format' => 'basic_html',
        ];
        $data['field_background'] = $this->expandImage($paragraph['image']);
        break;
      case 'cr_single_message_row':
        $data['field_single_msg_row_lr_title'] = [
          'value' => $paragraph['title'],
        ];
        $data['field_single_msg_row_lr_variant'] = [
          'value' => $paragraph['variant'],
        ];
        $data['field_single_msg_row_lr_body'] = [
          'value' => $paragraph['body'],
          'format' => 'basic_html',
        ];
        $data['field_single_msg_row_lr_image'] = $this->expandImage($paragraph['image']);
        break;
    }

    $paragraph_item = \Drupal\paragraphs\Entity\Paragraph::create($data);
    $paragraph_item->save();
    return $paragraph_item;
  }

  /**
   * Process image field values so we can use images.
   *
   * Shamelessly ripped off from \Drupal\Driver\Fields\Drupal8\ImageHandler
   *
   * We need to provide our own field handlers since we can't use the ones provided by AbstractCore::expandEntityFields as they are protected.
   *
   * @param  [type] $values [description]
   * @return [type]         [description]
   */
  private function expandImage($value) {
    // Skip empty values
    if (!$value) {
      return array();
    }

    $data = file_get_contents($value);
    if (FALSE === $data) {
      throw new \Exception("Error reading file");
    }

    /* @var \Drupal\file\FileInterface $file */
    $file = file_save_data(
      $data,
      'public://' . uniqid() . '.jpg');

    if (FALSE === $file) {
      throw new \Exception("Error saving file");
    }

    $file->save();

    $return = array(
      'target_id' => $file->id(),
      'alt' => 'Behat test image',
      'title' => 'Behat test image',
    );
    return $return;
  }

    /**
     * @BeforeScenario
     *
     * @param BeforeScenarioScope $scope
     *
     */
    public function before($scope)
    {
        $this->currentScenario = $scope->getScenario();
        $sampleData = [];
    }

    /**
     * @AfterStep
     *
     * @param AfterStepScope $scope
     */
    public function afterStep($scope)
    {
        //if test has failed, and is not an api test, get screenshot
        if(!$scope->getTestResult()->isPassed())
        {
            //create filename string
            $featureFolder = str_replace(' ', '', $scope->getFeature()->getTitle());

            $scenarioName = $this->currentScenario->getTitle();
            $fileName = str_replace(' ', '', $scenarioName) . '.png';

            //create screenshots directory if it doesn't exist
            if (!file_exists(__DIR__ . '/../../tmp/build/html/behat/assets/screenshots/' . $featureFolder)) {
                mkdir(__DIR__ . '/../../tmp/build/html/behat/assets/screenshots/' . $featureFolder, null, true);
            }

            //take screenshot and save as the previously defined filename
            file_put_contents(__DIR__ . '/../../tmp/build/html/behat/assets/screenshots/' . $featureFolder . '/' . $fileName, $this->getSession()->getDriver()->getScreenshot());
        }
    }

    /**
     * @BeforeSuite
     */
    public static function setup($scope)
    {
        //Function to delete files and folders from a directory
        /*function rrmdir($dir) {
            if (is_dir($dir)) {
                $objects = scandir($dir);
                foreach ($objects as $object) {
                    if ($object != "." && $object != "..") {
                        if (filetype($dir."/".$object) == "dir")
                            rrmdir($dir."/".$object);
                        else unlink   ($dir."/".$object);
                    }
                }
                reset($objects);
                rmdir($dir);
            }
        }

        //Check if screenshots folder exists and delete all files and folders from if
        if (file_exists(__DIR__ . '/../../tmp/build/html/behat/assets/screenshots/' )) {
            rrmdir(__DIR__ . '/../../tmp/build/html/behat/assets/screenshots/');
        }*/

    }

}

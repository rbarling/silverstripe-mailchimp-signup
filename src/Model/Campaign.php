<?php

namespace Innoweb\MailChimpSignup\Model;

use Innoweb\MailChimpSignup\Pages\CampaignListPage;
use SilverStripe\ORM\DataObject;

class Campaign extends DataObject {

    private static $table_name = 'MailChimpSignupCampaign';

    private static $db = [
        'MailChimpID'   =>  'Varchar(255)',
        'Title'         =>  'Varchar(255)',
        'Subject'       =>  'Varchar(255)',
        'SentDate'      =>  'Datetime',
        'URL'           =>  'Varchar(1024)',
        'Hidden'        =>  'Boolean',
        'ListID'        =>  'Varchar(50)',
        'SentToSegment' =>  'Boolean'
    ];

    private static $has_one = [
        'Page'          =>  CampaignListPage::class
    ];

    private static $summary_fields = [
        'Title'         =>  'Title',
        'Description'   =>  'Description',
        'SentDate.Nice' =>  'Sent',
        'Status'        =>  'Status'
    ];

    private static $casting = [
        'Status'        =>  'Varchar(50)'
    ];

    private static $default_sort = 'SentDate DESC';

    public function getLink()
    {
        return $this->URL;
    }

    public function getStatus()
    {
        $page = $this->Page();
        if ($this->Hidden) {
            return _t('Innoweb\\MailChimpSignup\\Model\\Campaign.StatusHiddenManual', 'Hidden (manual)');
        } else if (
            ($this->SentToSegment && $page->HideSentToSegments)
            || ($this->ListID && is_array($page->ListIDs) && in_array($this->ListID, $page->ListIDs))
        ) {
            return _t('Innoweb\\MailChimpSignup\\Model\\Campaign.StatusHiddenFilter', 'Hidden (filter)');
        } else {
            return _t('Innoweb\\MailChimpSignup\\Model\\Campaign.StatusVisible', 'Visible');
        }
    }
}
<?php
/** Form for creating an account for a user
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/

class AccountForm extends Pas_Form {

    /** The constructor
     * @access @access public
     * @param array $options
     */
    public function __construct(array $options = null) {
        parent::__construct($options);
        $this->init();
    }



    /** Initalise the form
     * 
     */
    public function init() {

        $username = $this->addElement('text','username',
                array('label' => 'Username: '))->username;
        $username->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('StringLength', true, array('max' => 40))
                ->setRequired(true);
        $username->getValidator('StringLength')
                ->setMessage('Username is too long');

        $password = $this->addElement('password', 'password',
            array('label' => 'Password'))->password;
        $password->addValidator('StringLength', true, array(6))
                 ->addValidator('Regex', true, 
                         array('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/'))
                 ->setRequired(true)
                 ->addErrorMessage('Please enter a valid password!');
        $password->getValidator('StringLength')
                ->setMessage('Password is too short');
        $password->getValidator('Regex')
                ->setMessage('Password does not contain letters and numbers');

        $firstName = $this->addElement('text', 'first_name',
                array('label' => 'First Name', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('You must enter a firstname');

        $lastName = $this->addElement('text', 'last_name',
                array('label' => 'Last Name', 'size' => '30'))->last_name;
        $lastName->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                 ->addErrorMessage('You must enter a surname');

       $fullname = $this->addElement('text', 'fullname',
                array('label' => 'Preferred Name: ', 'size' => '30'))->fullname;
       $fullname->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('You must enter your preferred name');

       $email = $this->addElement('text', 'email',
               array('label' => 'Email Address', 'size' => '30'))->email;
       $email->addValidator('EmailAddress')
                ->setRequired(true)
                ->addFilters(array('StringToLower', 'StripTags', 'StringTrim'))
                ->addErrorMessage('Please enter a valid address!');

       $institution = $this->addElement('text', 'institution', 
               array('label' => 'Recording institution: ', 'size' => '30'))->institution;


       $researchOutline = $this->addElement('textArea','research_outline',
                                    array(
                                        'label' => 'Outline your research', 
                                        'rows' => 10, 'cols' => 40)
               )->research_outline;

       $researchOutline->setRequired(false)
                ->addFilter('HtmlBody')
                ->addFilter('EmptyParagraph');

        $reference = $this->addElement('text','reference',
                array(
                    'label' => 'Please provide a referee:', 
                    'size' => '40')
                )->reference;

        $reference->setRequired(false)
                ->addFilters(array('StripTags', 'StringTrim'));

        $referenceEmail = $this->addElement('text','reference_email',
                    array(
                        'label' => 'Please provide an email address for your referee:',
                        'size' => '40')
                )->reference_email;
        $referenceEmail->setRequired(false)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('EmailAddress');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set my account up on Beowulf');

        $this->addElements(array($submit));

        $this->addDisplayGroup(array(
            'username', 'password', 'first_name',
            'last_name', 'fullname', 'email',
            'institution', 'research_outline', 'reference',
            'reference_email'),
            'userdetails');

        $this->addDisplayGroup(array('submit'), 'buttons');

        $this->setLegend('Edit account details: ');
        parent::init();
    }
}
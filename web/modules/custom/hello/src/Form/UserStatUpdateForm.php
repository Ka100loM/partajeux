<?php

    namespace Drupal\hello\Form;

  use Drupal\Core\Form\ConfigFormBase;  
  use Drupal\Core\Form\FormStateInterface;
  
  /**
   * Implements an admin form.
   */

   class UserStatUpdateForm extends ConfigFormBase {

     /**
      * {@inheritdoc}.
      */
      public function getFormID(){
          return 'admin_form';
      }

      /**
       * {@inheritdoc}
       */
       protected function getEditableConfigNames(){
           return ['hello.settings'];
       }

       /**
        * {@inheritdoc}
        */
        public function buildForm(array $form, FormStateInterface $form_state){
            $value =  $this->config('hello.settings')->get('purge_week_number');
            $form['purge_week_number'] = [
                '#type' => 'select',
                '#title' => $this
                  ->t('How long to keep user statistics'),
                '#options' => [
                  '0' => $this->t('None'),
                  '1' => $this->t('One day'),
                  '2' => $this->t('Two day\'s'),
                  '7' => $this->t('One week'),
                  '14' => $this->t('Two week\'s'),
                  '30' => $this->t('One month'),
                ],
                '#default_value' => $value,
              ];

            // $form['Save configuration'] = array(
            //     '#type' => 'submit',
            //     '#title' => 'Save configuration',
            //     '#value' => 'Save_configuration'
            // );
            return parent::buildForm($form, $form_state);
        }

        /**
         * {@inheritdoc}
         */
        public function submitForm(array &$form, FormStateInterface $form_state) {
            parent::submitForm($form, $form_state);

            $value_1 = $form_state->getValue('purge_week_number');
            $this->config('hello.settings')->set('purge_week_number', $value_1)->save(); 
        }
   }
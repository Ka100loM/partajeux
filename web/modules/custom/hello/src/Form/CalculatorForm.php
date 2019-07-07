<?php
    namespace Drupal\hello\Form;

    use Drupal\Core\Form\FormBase;
    use Drupal\Core\Form\FormStateInterface;
    use Drupal\Core\Ajax\AjaxResponse;
    use Drupal\Core\Ajax\CssCommand;
    use Drupal\Core\Ajax\HtmlCommand;

    class CalculatorForm extends FormBase{

        public function getFormID(){
            return 'hello_form';
        }

        public function buildForm(array $form, FormStateInterface $form_state){
           
            if(isset($form_state->getRebuildInfo()['result'])){
                $form['result'] = [
                    '#markup' => '<h2>'. $this->t('Result: ') . $form_state->getRebuildInfo()['result']. '</h2>',
                ];
                
            }
            $form['first_value'] = array(
                '#type' => 'textfield',
                '#title' => 'First Value',
                '#required' => TRUE,
                '#description' => $this->t('Enter first value.'),
                '#ajax'  => [
                    'callback' => [$this, 'AjaxValidateNumeric'],
                    'event' => 'mouseout',
                  ],
                  '#prefix' => '<span id="error-message-first_value"></span>',
            );

            $form['operation'] = array(
                '#type' => 'radios',
                '#title' => 'Operations',
                '#options' => array(
                     '+' => 'Ajouter' ,
                     '-' => 'Substract' ,
                     '*' => 'Multiply',
                     '/' => 'Divide'
                ),
                '#default_value' => '+',
                '#description' => $this->t('Choose operation for processing.')
            );

            $form['second_value'] = array(
                '#type' => 'textfield',
                '#title' => 'Second Value',
                '#required' => TRUE,
                '#description' => $this->t('Enter second value.'),
                '#ajax' => [
                    'callback' => [$this, 'AjaxValidateNumeric'],
                    'event' => 'mouseout',
                  ],
                  '#prefix' => '<span id="error-message-second_value"></span>',
            );

            $form['calculate'] = array(
                '#type' => 'submit',
                '#title' => 'Second Value',
                '#value' => 'Calculate'
            );

            // $form['calculate'] = array(
            //     '#type' => 'button',
            //     '#title' => 'Reset',
            //     '#ajax' => [
            //         'callback' => [$this, 'AjaxValidateNumeric'],
            //         'event' => 'mouseout',
            //       ],
            //       '#prefix' => '<span id="error-message-second_value"></span>',
            // );
         

           

            return $form;
        }

        public function AjaxValidateNumeric(array &$form, FormStateInterface $form_state) {
            $response = new AjaxResponse();
        
            $field = $form_state->getTriggeringElement()['#name'];
        
            if (is_numeric($form_state->getValue($field))) {
              $css = ['border' => '2px solid green'];
              $message = $this->t('OK!');
            } else {
              $css = ['border' => '2px solid red'];
              $message = $this->t('%field must be numeric!', ['%field' => $form[$field]['#title']]);
            }
        
            $response->AddCommand(new CssCommand("[name=$field]", $css));
            $response->AddCommand(new HtmlCommand('#error-message-' . $field, $message));
        
            return $response;
          }

        public function submitForm(array &$form, FormStateInterface $form_state){
            $value_1 = $form_state->getValue('first_value');
            $value_2 = $form_state->getValue('second_value');
            $operation = $form_state->getValue('operation');
            $result = '';

            if($operation == '+'){
                $result = $value_1 + $value_2;
            }
            if($operation == '-'){
                $result = $value_1 - $value_2;
            }

            if($operation == '*'){
                $result = $value_1 * $value_2;
            }

            if($operation == '/'){
                $result = $value_1 / $value_2;
            }

         
            
            $form_state->cleanValues();
         
            $form_state->addRebuildInfo('result', $result);
            $form_state->setRebuild();
            
          
           // \Drupal::messenger()->addMessage(t('%result', ['%result' => $result]));
            

        }

        public function validateForm(array &$form, FormStateInterface $form_state){
            $value_1 = $form_state->getValue('first_value');
            $value_2 = $form_state->getValue('second_value');
            $operation = $form_state->getValue('operation');
            if(!is_numeric($value_1)){
                $form_state->setErrorByName('first_value', $this->t('First value must be numeric'));
            }
            if(!is_numeric($value_2)){
                $form_state->setErrorByName('second_value', $this->t("Second value must be numeric"));
            }
            if($value_2 == 0 && $operation == '/'){
                $form_state->setErrorByName('operation', $this->t('Second value must be different from 0 on Divide operation'));
            }
          
        }
    }
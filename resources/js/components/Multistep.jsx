import React, { useState } from 'react';
import { AppProvider, FormLayout, TextField, Button, ProgressBar } from '@shopify/polaris';

const steps = ['Step 1', 'Step 2', 'Step 3', 'Step 4'];

function MultiStepForm()  {
  const [step, setStep] = useState(0);
  const [formData, setFormData] = useState({});

  const handleInputChange = (field, value) => {
    setFormData({...formData, [field]: value});
  };

  const handleSubmit = () => {
    // Submit form data to server
  };

  return (
    <AppProvider>
      <FormLayout>
      <ProgressBar progress={(step + 1) * 33} />
        {step === 0 && (
          <TextField label="Field 1" value={formData.field1} onChange={(value) => handleInputChange('field1', value)} />
        )}
        {step === 1 && (
          <TextField label="Field 2" value={formData.field2} onChange={(value) => handleInputChange('field2', value)} />
        )}
        {step === 2 && (
          <TextField label="Field 3" value={formData.field3} onChange={(value) => handleInputChange('field3', value)} />
        )}
        {step === 3 && (
          <TextField label="Field 4" value={formData.field4} onChange={(value) => handleInputChange('field4', value)} />
        )}
        <Button primary disabled={step === 0} onClick={() => setStep(step - 1)}>
          Back
        </Button>
        <Button primary disabled={!formData[`field${step + 1}`]} onClick={() => setStep(step + 1)}>
          Next
        </Button>
        {step === 3 && (
          <Button primary onClick={handleSubmit}>
            Submit2
          </Button>
        )}
      </FormLayout>
    </AppProvider>
  );
};

function Create(){

  return (
    <AppProvider>
        <MultiStepForm/>
    </AppProvider>
  );
}

export default Create;

import { useState } from "react";
import {
  Card,
  TextContainer,
  DropZone,
  Thumbnail,
} from "@shopify/polaris";
import {NoteMinor} from '@shopify/polaris-icons';
import { Toast } from "@shopify/app-bridge-react";
import { useAppQuery, useAuthenticatedFetch } from "../hooks";

export function FileImportCard() {
  const emptyToastProps = { content: null };
  const [files, setFiles] = useState([]);
  const handleDrop = (droppedFiles) => {
    setFiles(droppedFiles);
  };
  const handleRemove = () => {
    setFiles([]);
  };
  const validImageTypes = ['image/gif', 'image/jpeg', 'image/png'];
  const [isLoading, setIsLoading] = useState(false);
  const [toastProps, setToastProps] = useState(emptyToastProps);
  const fetch = useAuthenticatedFetch();
 
  const toastMarkup =
  toastProps.content && !isLoading && !files.length && (
    <Toast {...toastProps} onDismiss={() => setToastProps(emptyToastProps)} />
  );
  const handlePopulate = async () => { 
    if(files.length === 0) {
      setToastProps({
        content: "Please select a file to upload! ",
        error: true,
      });
      return;
    }
    setIsLoading(true);
    const file = files[0];  
    var formData = new FormData()
    formData.append('myfile', file)
    const response = await fetch('/api/import', {
      method: 'POST',
      body: formData
  });
  console.log(response)
    if (response.ok) {
      setToastProps({ content: "File imported successfully, customers will create in background!" });
      setIsLoading(false);
    setFiles("");
    } else {
      setIsLoading(false);
      setFiles("");
      setToastProps({
        content: "There was an error importing customers",
        error: true,
      });
    }
  };
  
  return (
    <>
      {toastMarkup}
      <Card
        title="Customers Import"
        sectioned
        primaryFooterAction={{
          content: "Import",
          onAction: handlePopulate,
          loading: isLoading,
        }}
      >
        <TextContainer spacing="loose">
          <p>
          Upload an excel file to import customers on your shopify store.
          </p>
          <DropZone label="Customer file"
          type="file"
          accept=".xlsx, .xls, .csv"
          placeholder="files"
          
          onDrop={handleDrop}
          onRemove={handleRemove}
          >
        {files.length > 0 && (
           <div style={{display:'flex',flexDirection:'column',placeContent:'center', flexWrap:'wrap',marginTop:'10px' }}>
           <Thumbnail
             source={
               validImageTypes.includes(files[0].type)
                 ? window.URL.createObjectURL(files[0])
                 : NoteMinor
             }
             size="large"
             alt={files[0].name}
            
           />
            <div>{files[0].name}</div>
           </div>
        )}
        {files.length == 0 && (
          <DropZone.FileUpload actionHint="Accepts .xlsx, .xls, and .csv" /> 
        )}
        
       </DropZone>
        </TextContainer>
      </Card>
    </>
  );
}

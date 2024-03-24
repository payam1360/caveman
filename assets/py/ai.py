# simple pyTorch ai assistant.     
from transformers import pipeline
import torch
import json
import sys
# Check if GPU is available
device = "cuda" if torch.cuda.is_available() else "cpu"
# Initialize pipeline with device parameter
# Read input from command-line argument
messages_json = sys.argv[1]
# Convert JSON string to Python list
messages = json.loads(messages_json)
# Load pre-trained model and tokenizer
model_name = "HuggingFaceH4/zephyr-7b-beta" 
# Create a text generation pipeline
pipe = pipeline("text-generation", model=model_name)
prompt = pipe.tokenizer.apply_chat_template(messages, tokenize=False, add_generation_prompt=True)
output = pipe(prompt, max_new_tokens=10, do_sample=True, temperature=0.4, top_k=50, top_p=0.65)
output = output[0]['generated_text'] + ' DONE'
# Print generated text for the current prompt word by word
print(json.dumps(output))   


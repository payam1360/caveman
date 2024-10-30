# simple pyTorch ai assistant.     
from transformers import pipeline
from transformers import logging
from transformers import AutoModelForCausalLM, AutoTokenizer
import torch
import json
import sys
import os
os.environ['TRANSFORMERS_NO_TQDM'] = '1'
logging.set_verbosity_error()  # Or set_verbosity_warning() to show only warnings
cache_dir = os.path.expanduser("/Users/payamrabiei/.cache/huggingface/hub/")  # Ensure expanded path

model_name = "HuggingFaceH4/zephyr-7b-beta"
# Download the model and tokenizer to the local cache directory
tokenizer = AutoTokenizer.from_pretrained(model_name, cache_dir=cache_dir, force_download=False)
model     = AutoModelForCausalLM.from_pretrained(model_name, cache_dir=cache_dir, force_download=False)
# Check if GPU is available
device = "cuda" if torch.cuda.is_available() else "cpu"
# Initialize pipeline with device parameter
# Read input from command-line argument
messages_json = sys.argv[1]
input_token_size = int(sys.argv[2])
# Convert JSON string to Python list
messages = json.loads(messages_json)

# Create a text generation pipeline
pipe = pipeline("text-generation", model=model, tokenizer=tokenizer, device=device)

prompt = pipe.tokenizer.apply_chat_template(messages, tokenize=False, add_generation_prompt=True)
output = pipe(prompt, max_new_tokens=input_token_size, do_sample=True, temperature=0.4, top_k=50, top_p=0.65)
output = output[0]['generated_text'] + ' DONE'
# Print generated text for the current prompt word by word 
print(json.dumps(output)) 
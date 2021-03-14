class Rape{
constructor(file,conf){
this._='20210315';
this.canvas=file||null;
this.config=conf||{};
console.log('//github.com/netcrashed/rape.js?'+this._);
};
code(part,exec){
if(typeof(exec)=='undefined'){return [...new Uint8Array(part)].map(b=>b.toString(16).padStart(2,'0')).join('');}
else if(exec){let temp='';while(part.length>2){temp+=part.slice(-2);part=part.slice(0,-2);};return temp+part;}
else{return part;};
};
read(from,till){
return new Promise((resolve,reject)=>{
if(typeof(this.canvas)=='object'){
this.length=this.canvas.size;
if(this.length<60){return reject('read:length');};
if(!from||from<0){from=from?(this.length+from):0;};
if(!till||till<0){till=this.length+(till||0);};
if(till>this.length){till=this.length;};
this.reader=this.reader||new FileReader();
this.reader.readAsArrayBuffer(this.canvas.slice(from,till));
this.reader.onerror=()=>{return reject('read:error');};
this.reader.onload=()=>{return resolve(this.code(this.reader.result));};
}
else if(typeof(this.canvas)=='string'&&this.canvas.slice(0,4)=='blob'){
if(this.reader){
if(!from||from<0){from=from?(this.length+from):0;};
if(!till||till<0){till=this.length+(till||0);};
if(till>this.length){till=this.length;};
return resolve(this.code(this.reader.response.slice(from,till)));
}
else{
this.reader=new XMLHttpRequest;
this.reader.responseType='arraybuffer';
this.reader.onerror=()=>{return reject('read:error');};
this.reader.onload=()=>{
this.length=this.reader.response.byteLength;
if(this.length<60){return reject('read:length');};
if(!from||from<0){from=from?(this.length+from):0;};
if(!till||till<0){till=this.length+(till||0);};
if(till>this.length){till=this.length;};
return resolve(this.code(this.reader.response.slice(from,till)));
};
this.reader.open('GET',this.canvas);
this.reader.send(null);
};
}
else if(typeof(this.canvas)=='string'&&this.canvas.slice(0,4)=='data'){
let head=this.canvas.indexOf(',')+1;if(head<=0){return reject('read:error');};
this.length=(this.canvas.length-head)*0.75-(this.canvas.slice(-2).match(/=/g)||[]).length;
if(this.length<60){return reject('read:length');};
if(!from||from<0){from=from?(this.length+from):0;};
if(!till||till<0){till=this.length+(till||0);};
if(till>this.length){till=this.length;};
return resolve(this.code(Uint8Array.from(atob(this.canvas.slice(head+Math.floor(from/3)*4,head+Math.ceil(till/3)*4)),c=>c.charCodeAt(0)).buffer.slice(from%3,(till%3||3)-3||this.length)));
}
else{return reject('read:input');};
});
};
load(item){
return new Promise((resolve,reject)=>{
let loader=new Image();
loader.src=(typeof(item)=='object')?URL.createObjectURL(item):item;
loader.onload=()=>{return resolve(loader);};
loader.onerror=()=>{return reject('load:error');};
});
};
bath(){
return new Promise((resolve,reject)=>{
if(typeof(this.canvas)=='object'&&this.config._dataurl){
let reader=new FileReader();
reader.readAsDataURL(this.canvas);
reader.onerror=()=>{return reject('bath:error');};
reader.onload=()=>{this.canvas=reader.result;return resolve();};
}
else if(typeof(this.canvas)=='string'&&(this.canvas.slice(0,4)=='blob'||!this.config._dataurl)){
let reader=new XMLHttpRequest;
reader.responseType='blob';
reader.onerror=()=>{return reject('bath:error');};
reader.onload=()=>{
if(!this.config._dataurl){this.canvas=reader.response;return resolve();};
let _reader=new FileReader();
_reader.readAsDataURL(reader.response);
_reader.onerror=()=>{return reject('bath:error');};
_reader.onload=()=>{this.canvas=_reader.result;return resolve();};
};
reader.open('GET',this.canvas);
reader.send(null);
}
else{return resolve();};
});
};
async init(){
if(!this.canvas){return Promise.reject('init:canvas');};
let head=await this.read(0,41);
if(head.slice(0,16)=='89504e470d0a1a0a'){this.format='png';if(head.slice(74,82)=='6163544c'){this.animate=true;};}
else if(head.slice(0,6)=='ffd8ff'){this.format='jpg';}
else if(head.slice(0,4)=='424d'){this.format='bmp';}
else if(head.slice(0,8)=='00000100'){this.format='ico';}
else if(head.slice(0,8)=='00000200'){this.format='ico';this.detail='cur';}
else if(head.slice(0,12)=='474946383761'){this.format='gif';this.detail='gif87';}
else if(head.slice(0,12)=='474946383961'){this.format='gif';this.detail='gif89';}
else if(head.slice(0,8)=='52494646'&&head.slice(16,24)=='57454250'){this.format='webp';if(head.slice(24,32)=='56503858'&&parseInt(head.slice(40,42),16).toString(2).slice(-2,-1)==1){this.animate=true;};}
else{return Promise.reject('init:format');};
if(this.format=='jpg'){
let skip=0;switch(head.slice(4,8)){case 'ffe0':skip=parseInt(head.slice(8,12),16)+8;break;case 'ffe1':skip=6;break;};
if(skip){
let chip=((skip*2+28)>head.length)?await this.read(skip,skip+14):head.slice(skip*2,skip*2+28);
if(chip.slice(0,8)=='45786966'){
let lend=(chip.slice(12,16)=='4949');skip+=parseInt(this.code(chip.slice(20,28),lend),16)+6;
chip=((skip*2+4)>head.length)?await this.read(skip,skip+2):head.slice(skip*2,skip*2+4);
let loop=parseInt(this.code(chip,lend),16);skip+=2;
chip=await this.read(skip,skip+loop*12);
for(let i=0;i<loop;i++){if(['0112','1201'].indexOf(chip.slice(i*24,i*24+4))>=0){this.rotate=parseInt(this.code(chip.slice(i*24+16,i*24+20),lend),16);break;};};
};
};
};
if(this.format=='gif'&&this.detail=='gif89'){
let from=0,find=0;
while(from>=0&&find<2){
find+=((await this.read(from,from+102420)).match(/21f904.{8}00(2c|21)/g)||[]).length;
if(from+102420>=this.length){from=-1;}else{from+=102400;};
};
if(find>=2){this.animate=true;};
};
if(this.reader){delete this.reader;};
return Promise.resolve(this);
};
async conv(){
await this.init();
if(!this.config[this.format]){await this.bath();return Promise.resolve(this);};
let cfg=this.config[this.format].normal?this.config[this.config[this.format].normal]:{};
if(this.animate&&typeof(this.config[this.format].animate)!='undefined'){
if(this.config[this.format].animate){cfg=this.config[this.config[this.format].animate];}
else{await this.bath();return Promise.resolve(this);};
};
if(cfg.format=='image/webp'&&typeof(this.config[this.format].nowebp)!='undefined'){
let cvs=document.createElement('canvas');cvs.getContext('2d');
if(cvs.toDataURL('image/webp').indexOf('data:image/webp')!=0){
if(this.config[this.format].nowebp){cfg=this.config[this.config[this.format].nowebp];}
else{await this.bath();return Promise.resolve(this);};
};
};
let img=await this.load(this.canvas);
let fix=(typeof(img.style['image-orientation'])=='undefined')?true:(['','from-image'].indexOf(img.style['image-orientation'])<0);
if(typeof(this.canvas)=='object'){URL.revokeObjectURL(img.src);};
let w=img.width,h=img.height,r=w/h,swap=false;
if(fix&&(this.rotate==6||this.rotate==8)){w=img.height;h=img.width;r=w/h;swap=true;};
if(cfg.width&&w>cfg.width&&cfg.height&&h>cfg.height){if(cfg.height>cfg.width/r){w=cfg.width;h=w/r;}else{h=cfg.height;w=h*r;};}
else if(cfg.width&&w>cfg.width){w=cfg.width;h=Math.round(w/r);}
else if(cfg.height&&h>cfg.height){h=cfg.height;w=Math.round(h*r);};
this.canvas=document.createElement('canvas');
this.canvas.width=w;
this.canvas.height=h;
let ctx=this.canvas.getContext('2d');
if(fix){
switch(this.rotate){
case 3:ctx.translate(w,h);ctx.rotate(Math.PI);break;
case 6:ctx.translate(w,0);ctx.rotate(Math.PI/2);break;
case 8:ctx.translate(0,h);ctx.rotate(Math.PI/-2);break;
};
};
ctx.fillStyle=cfg.fill||'transparent';
ctx.fillRect(0,0,w,h);
if(cfg.render){for(let key in cfg.render){ctx[key]=cfg.render[key];};};
ctx.drawImage(img,0,0,img.width,img.height,0,0,swap?h:w,swap?w:h);
if(this.config._chop&&this.config._chop.url){
let wmk=await this.load(this.config._chop.url),width=this.config._chop.width||0,height=this.config._chop.height||0,x=this.config._chop.x||0,y=this.config._chop.y||0;
if(x>0&&x<1){x=w*x-wmk.width/2;};
if(y>0&&y<1){y=h*y-wmk.height/2;};
if(w>=width&&h>=height){ctx.drawImage(wmk,0,0,wmk.width,wmk.height,(x<0)?(w+x):x,(y<0)?(h+y):y,wmk.width,wmk.height);};
};
if(this.config._dataurl){this.canvas=this.canvas.toDataURL(cfg.format||'image/png',cfg.quality||0.9);return Promise.resolve(this);}
else{
this.canvas=await new Promise((resolve,reject)=>{this.canvas.toBlob(function(blob){resolve(blob);},cfg.format||'image/png',cfg.quality||0.9);});
return Promise.resolve(this);
};
};
};
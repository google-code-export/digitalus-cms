/* http://keith-wood.name/svg.html
   SVG graphing extension for jQuery v1.0.0.
   Written by Keith Wood (kbwood@iprimus.com.au) August 2007.
   Under the Creative Commons Licence http://creativecommons.org/licenses/by/3.0/
   Share or Remix it but please Attribute the author. */
eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('6 1e=1a;(7($){43.3M(\'3E\',36);1e=1k 2Z();7 2Z(){4.2P=[];4.2P[\'\']={28:\'3K\'};4.2m=4.2P[\'\']}$.K(2Z.1r,{23:[],1U:7(a,b){4.23[a]=b},49:7(){F 4.23}});7 36(a){4.E=a;4.2r=17;O(6 b 2J 1e.23){4.2s=1e.23[b];3c}4.1g={};4.Q={35:\'\',2h:25,2B:{19:\'1j\'}};4.1G=[0.1,0.1,0.8,0.9];4.2u={1u:\'3s\',1c:\'2T\'};4.1l=[];4.U=[];4.2k=1a;4.M=4.E.Z(1a,\'3E\');4.V=1k 29();4.V.1q(\'\',40);4.11=1k 29();4.11.1q(\'\',40);4.1L=1a;4.1N=1a;4.1t=1k 2Y()}$.K(36.1r,{X:0,Y:1,W:2,H:3,L:0,T:1,R:2,B:3,31:1k 29(1e.2m.28,0,2g,10,0),4l:7(a,b){G(18.J==0){F 4.2s}6 c=1e.23[a];G(c){4.2s=c;4.1g=$.K({},b||{})}4.1A();F 4},4k:7(a){G(18.J==0){F 4.1g}4.1g=$.K({},a);4.1A();F 4},4j:7(a,b,c){G(18.J==0){F 4.2u}G(1v b==\'1S\'){c=b;b=1a}4.2u=$.K($.K({1u:a},(b?{1c:b}:{})),c||{});4.1A();F 4},4g:7(a,b,c,d){G(18.J==0){F 4.1G}4.1G=(2v(a)?a:[a,b,c,d]);4.1A();F 4},4b:7(a,b){G(18.J==0){F 4.1l}4.1l=[(1v a==\'2W\'?{1c:a}:a),(1v b==\'2W\'?{1c:b}:b)];4.1A();F 4},1q:7(a,b,c){G(18.J==0){F 4.Q}G(1v b==\'1S\'){c=b;b=1a}4.Q={35:a,2h:b||4.Q.2h,2B:$.K({19:\'1j\'},c||{})};4.1A();F 4},48:7(a,b,c,d,e,f){G(1v e==\'1S\'){f=e;e=1a}4.U[4.U.J]=1k 2V(a,b,c,d,e,f||{});4.1A();F 4},1P:7(){F 4.U},3Z:7(){4.2r=1O;F 4},3V:7(){4.2r=17;4.1A();F 4},3T:7(a){4.2k=a;F 4},1A:7(){G(!4.2r){F}2S(4.M.3p){4.M.3R(4.M.3p)}G(!4.M.2Q){4.E.3O.3N(4.M)}4.2s.1R(4)},1M:7(){4.E.14(4.M,4.E.2p()/2,4.Q.2h,4.Q.35,4.Q.2B)},1x:7(a){a=a||4.1G;6 b=(a[4.L]>1?a[4.L]:4.E.2p()*a[4.L]);6 c=(a[4.T]>1?a[4.T]:4.E.2M()*a[4.T]);6 d=(a[4.R]>1?a[4.R]:4.E.2p()*a[4.R])-b;6 e=(a[4.B]>1?a[4.B]:4.E.2M()*a[4.B])-c;F[b,c,d,e]},1K:7(a,b){6 c=4.E.Z(4.M,\'3L\');6 d=4.1x();4.E.1J(c,d[4.X],d[4.Y],d[4.W],d[4.H],4.2u);G(4.1l[0]&&4.11.I.16&&!b){4.1X(c,4.11,17,d,4.1l[0])}G(4.1l[1]&&4.V.I.16&&!a){4.1X(c,4.V,1O,d,4.1l[1])}F c},1X:7(a,b,c,d,e){6 g=4.E.Z(a,e);6 f=(c?d[4.H]:d[4.W])/(b.N.1b-b.N.12);6 h=13.39(b.N.12/b.I.16)*b.I.16;h=(h<b.N.12?h+b.I.16:h);2S(h<=b.N.1b){6 v=(c?b.N.1b-h:h-b.N.12)*f+(c?d[4.Y]:d[4.X]);4.E.1h(g,(c?d[4.X]:v),(c?v:d[4.Y]),(c?d[4.X]+d[4.W]:v),(c?v:d[4.Y]+d[4.H]));h+=b.I.16}},2n:7(a){6 b=4.1x();G(4.V&&!a){G(4.V.Q){4.E.14(4.M,b[4.X]+b[4.W]/2,b[4.Y]+b[4.H]+4.V.1d,4.V.Q)}4.1I(4.V,\'V\',b[4.X],b[4.Y]+b[4.H],b[4.X]+b[4.W],b[4.Y]+b[4.H])}G(4.11){G(4.11.Q){4.E.14(4.M,0,0,4.11.Q,{19:\'1j\',2l:\'2d(\'+(b[4.X]-4.11.1d)+\',\'+(b[4.Y]+b[4.H]/2)+\') 2j(-2i)\'})}4.1I(4.11,\'11\',b[4.X],b[4.Y],b[4.X],b[4.Y]+b[4.H])}G(4.1L&&!a){G(4.1L.Q){4.E.14(4.M,b[4.X]+b[4.W]/2,b[4.X]-4.1L.1d,4.1L.Q)}4.1I(4.1L,\'1L\',b[4.X],b[4.Y],b[4.X]+b[4.W],b[4.Y])}G(4.1N){G(4.1N.Q){4.E.14(4.M,0,0,4.1N.Q,{19:\'1j\',2l:\'2d(\'+(b[4.X]+b[4.W]+4.1N.1d)+\',\'+(b[4.Y]+b[4.H]/2)+\') 2j(-2i)\'})}4.1I(4.1N,\'1N\',b[4.X]+b[4.W],b[4.Y],b[4.X]+b[4.W],b[4.Y]+b[4.H])}},1I:7(a,b,c,d,e,f){6 g=(d==f);6 h=4.E.Z(4.M,b,a.1D);6 i=4.E.Z(4.M,b+\'4u\',$.K({19:(g?\'1j\':\'34\')},a.1C));4.E.1h(h,c,d,e,f);G(a.I.16){6 j=(e>(4.E.2p()/2)&&f>(4.E.2M()/2));6 k=(g?e-c:f-d)/(a.N.1b-a.N.12);6 l=a.I.1f;6 m=13.39(a.N.12/a.I.16)*a.I.16;m=(m<a.N.12?m+a.I.16:m);6 n=(!a.I.1Q?a.N.1b+1:13.39(a.N.12/a.I.1Q)*a.I.1Q);n=(n<a.N.12?n+a.I.1Q:n);6 o=4.22(a,j);2S(m<=a.N.1b||n<=a.N.1b){6 p=13.12(m,n);6 q=(p==m?l:l/2);6 v=(g?c:d)+(g?p-a.N.12:a.N.1b-p)*k;4.E.1h(h,(g?v:c+q*o[0]),(g?d+q*o[0]:v),(g?v:c+q*o[1]),(g?d+q*o[1]:v));G(p==m){4.E.14(i,(g?v:c-l),(g?d+2*l:v),(a.1i?a.1i[p]:\'\'+p))}m+=(p==m?a.I.16:0);n+=(p==n?a.I.1Q:0)}}},22:7(a,b){F[(a.I.21==(b?\'2J\':\'2f\')||a.I.21==\'3x\'?-1:0),(a.I.21==(b?\'2f\':\'2J\')||a.I.21==\'3x\'?+1:0),]},2e:7(){4.31.Q=1e.2m.28;F 4.31},2A:7(){6 a=[];6 b=(4.U.J?4.U[0].P.J:0);O(6 i=0;i<b;i++){a[i]=0;O(6 j=0;j<4.U.J;j++){a[i]+=4.U[j].P[i]}}F a},1W:7(){G(!4.1t.2z){F}6 g=4.E.Z(4.M,\'1t\');6 a=4.1x(4.1t.1G);4.E.1J(g,a[4.X],a[4.Y],a[4.W],a[4.H],4.1t.2y);6 b=a[4.W]>a[4.H];6 c=4.U.J;6 d=(b?a[4.W]:a[4.H])/c;6 e=a[4.X]+5;6 f=a[4.Y]+(b?a[4.H]/2:d/2);O(6 i=0;i<c;i++){6 h=4.U[i];4.E.1J(g,e+(b?i*d:0),f+(b?0:i*d)-4.1t.1V,4.1t.1V,4.1t.1V,{1u:h.1B,1c:h.1p,1y:1});4.E.14(g,e+(b?i*d:0)+4.1t.1V+5,f+(b?0:i*d),h.1n,4.1t.2x)}},1T:7(a){6 b=(!4.2k?\'\':4.2k.3v().4i(/7 (.*)\\([\\s\\S]*/m,\'$1\'));F(!4.2k?{}:{4h:\'3u.2Q.\'+b+\'(\\\'\'+a+\'\\\');\',4f:\'3u.2Q.\'+b+\'(\\\'\\\');\'})}});7 29(a,b,c,d,e){4.Q=a||\'\';4.2w={};4.1d=0;4.1i=1a;4.1C={};4.1D={1c:\'2T\'};4.I={16:d||10,1Q:e||0,1f:10,21:\'2f\'};4.N={12:b||0,1b:c||2g};4.4e=0}$.K(29.1r,{4d:7(a,b){G(18.J==0){F 4.N}4.N.12=a;4.N.1b=b;F 4},4c:7(a,b,c,d){G(18.J==0){F 4.I}G(1v c==\'2W\'){d=c;c=1a}4.I.16=a;4.I.1Q=b;4.I.1f=c||10;4.I.21=d||\'2f\';F 4},1q:7(a,b,c){G(18.J==0){F{1q:4.Q,2h:4.1d,2X:4.2w}}G(1v b==\'1S\'){c=b;b=1a}4.Q=a;G(b!=1a){4.1d=b}G(c){4.2w=c}F 4},3t:7(a,b){G(18.J==0){F{3t:4.1i,2X:4.1C}}4.1i=a;G(b){4.1C=b}F 4},1h:7(a,b,c){G(18.J==0){F 4.1D}G(1v b==\'1S\'){c=b;b=1a}$.K(4.1D,{1c:a,1y:b||1});$.K(4.1D,c||{});F 4}});6 B=\'4a\';6 C=\'2T\';7 2V(a,b,c,d,e,f){4.1n=a||\'\';4.P=b||[];4.47=1;4.1B=c||B;4.1p=d||C;4.1w=e||1;4.1z=f||{}}$.K(2V.1r,{46:7(a){G(18.J==0){F 4.1n}4.1n=a;F 4},1F:7(a,b){G(18.J==0){F 4.P}G(2v(a)){45=a;a=1a}4.1n=a||4.1n;4.P=b;F 4},2X:7(a,b,c,d){G(18.J==0){F $.K({1u:4.1B,1c:4.1p,1y:4.1w},4.1z)}G(1v c==\'1S\'){d=c;c=1a}4.1B=a||B;4.1p=b||4.1p;4.1w=c||4.1w;$.K(4.1z,d||{});F 4}});7 2Y(a,b){4.2z=17;4.1G=[0.9,0.1,1.0,0.9];4.1V=15;4.2y=a||{1c:\'44\'};4.2x=b||{}}$.K(2Y.1r,{42:7(a){G(18.J==0){F 4.2z}4.2z=a;F 4},41:7(a,b,c,d){G(18.J==0){F 4.1G}4.1G=(2v(a)?a:[a,b,c,d]);F 4},2B:7(a,b,c){G(18.J==0){F{3Y:4.1V,3X:4.2y,3W:4.2x}}G(1v a==\'1S\'){c=b;b=a;a=1a}G(a){4.1V=a}4.2y=b;G(c){4.2x=c}F 4}});7 2t(a,b){F 13.3U(a*13.3q(10,b))/13.3q(10,b)}6 D=[\'27 (2b) - 1E 3S 1s 2R 3Q\',\'2a (2b) - 1E 3P 3o 20 1s 2q\'];7 2O(){}$.K(2O.1r,{1q:7(){F\'2N 30 1m\'},1Z:7(){F\'1Y 20 1s 1F 26 3n 2q 3m 3l 3B.\'},24:7(){F D},1R:7(a){a.1K(17);6 b=a.1g.27||10;6 c=a.1g.2a||10;6 d=a.U.J;6 e=(d?(a.U[0]).P.J:0);6 f=a.1x();6 g=f[a.W]/((d*b+c)*e+c);6 h=f[a.H]/(a.11.N.1b-a.11.N.12);4.1o=a.E.Z(a.M,\'1m\');O(6 i=0;i<d;i++){4.1H(a,i,d,b,c,f,g,h)}a.1M();a.2n(17);4.2o(a,d,e,b,c,f,g);a.1W()},1H:7(a,b,c,d,e,f,h,j){6 k=a.U[b];6 g=a.E.Z(4.1o,\'1P\'+b,$.K({1c:k.1p,1y:k.1w},k.1z||{}));O(6 i=0;i<k.P.J;i++){a.E.1J(g,f[a.X]+h*(e+i*(c*d+e)+(b*d)),f[a.Y]+j*(a.11.N.1b-k.P[i]),h*d,j*k.P[i],$.K({1u:k.1B},a.1T(k.1n+\' \'+k.P[i])))}},2o:7(a,b,c,d,e,f,g){6 h=a.V;G(h.Q){a.E.14(a.M,f[a.X]+f[a.W]/2,f[a.Y]+f[a.H]+h.1d,h.Q,{19:\'1j\'})}6 j=a.E.Z(a.M,\'V\',h.1D);6 k=a.E.Z(a.M,\'2L\',$.K({19:\'1j\'},h.1C));a.E.1h(j,f[a.X],f[a.Y]+f[a.H],f[a.X]+f[a.W],f[a.Y]+f[a.H]);G(h.I.16){6 l=a.22(h,17);O(6 i=1;i<c;i++){6 x=f[a.X]+g*(e/2+i*(b*d+e));a.E.1h(j,x,f[a.Y]+f[a.H]+l[0]*h.I.1f,x,f[a.Y]+f[a.H]+l[1]*h.I.1f)}O(6 i=0;i<c;i++){6 x=f[a.X]+g*(e/2+(i+0.5)*(b*d+e));a.E.14(k,x,f[a.Y]+f[a.H]+2*h.I.1f,(h.1i?h.1i[i]:\'\'+i))}}}});7 2K(){}$.K(2K.1r,{1q:7(){F\'3k 30 1m\'},1Z:7(){F\'1Y 20 1s 1F 26 3n 2q 3j \'+\'2F 2I 2c 1E 2H O 2R 3i.\'},24:7(){F D},1R:7(a){6 b=a.1K(17,17);6 c=a.1x();G(a.1l[0]&&a.V.I.16){a.1X(b,a.2e(),17,c,a.1l[0])}6 d=a.1g.27||10;6 e=a.1g.2a||10;6 f=a.U.J;6 g=(f?(a.U[0]).P.J:0);6 h=c[a.W]/((d+e)*g+e);6 i=c[a.H];4.1o=a.E.Z(a.M,\'1m\');4.3h(a,f,g,d,e,c,h,i);a.1M();a.E.14(a.M,0,0,1e.2m.28,{19:\'1j\',2l:\'2d(\'+(c[a.X]-a.11.1d)+\',\'+(c[a.Y]+c[a.H]/2)+\') 2j(-2i)\'});a.1I(a.2e(),\'11\',c[a.X],c[a.Y],c[a.X],c[a.Y]+c[a.H]);4.2o(a,g,d,e,c,h);a.1W()},3h:7(a,b,c,d,e,f,h,j){6 k=a.2A();6 l=[];O(6 i=0;i<c;i++){l[i]=0}O(6 s=0;s<b;s++){6 m=a.U[s];6 g=a.E.Z(4.1o,\'1P\'+s,$.K({1c:m.1p,1y:m.1w},m.1z||{}));O(6 i=0;i<m.P.J;i++){l[i]+=m.P[i];a.E.1J(g,f[a.X]+h*(e+i*(d+e)),f[a.Y]+j*(k[i]-l[i])/k[i],h*d,j*m.P[i]/k[i],$.K({1u:m.1B},a.1T(m.1n+\' \'+2t(m.P[i]/k[i]*2g,2)+\'%\')))}}},2o:7(a,b,c,d,e,f){6 g=a.V;G(g.Q){a.E.14(a.M,e[a.X]+e[a.W]/2,e[a.Y]+e[a.H]+g.1d,g.Q,{19:\'1j\'})}6 h=a.E.Z(a.M,\'V\',g.1D);6 j=a.E.Z(a.M,\'2L\',$.K({19:\'1j\'},g.1C));a.E.1h(h,e[a.X],e[a.Y]+e[a.H],e[a.X]+e[a.W],e[a.Y]+e[a.H]);G(g.I.16){6 k=a.22(g,17);O(6 i=1;i<b;i++){6 x=e[a.X]+f*(d/2+i*(c+d));a.E.1h(h,x,e[a.Y]+e[a.H]+k[0]*g.I.1f,x,e[a.Y]+e[a.H]+k[1]*g.I.1f)}O(6 i=0;i<b;i++){6 x=e[a.X]+f*(d/2+(i+0.5)*(c+d));a.E.14(j,x,e[a.Y]+e[a.H]+2*g.I.1f,(g.1i?g.1i[i]:\'\'+i))}}}});7 2U(){}$.K(2U.1r,{1q:7(){F\'2N 2G 1m\'},1Z:7(){F\'1Y 20 1s 1F 26 3g 3J 3m 3l 3B.\'},24:7(){F D},1R:7(a){6 b=a.1K(17,17);6 c=a.1x();a.1X(b,a.11,1O,c,a.1l[0]);6 d=a.1g.27||10;6 e=a.1g.2a||10;6 f=a.U.J;6 g=(f?(a.U[0]).P.J:0);6 h=c[a.W]/(a.11.N.1b-a.11.N.12);6 j=c[a.H]/((f*d+e)*g+e);4.1o=a.E.Z(a.M,\'1m\');O(6 i=0;i<f;i++){4.1H(a,i,f,d,e,c,h,j)}a.1M();4.2n(a,f,g,d,e,c,j);a.1W()},1H:7(a,b,c,d,e,f,h,j){6 k=a.U[b];6 g=a.E.Z(4.1o,\'1P\'+b,$.K({1c:k.1p,1y:k.1w},k.1z||{}));O(6 i=0;i<k.P.J;i++){a.E.1J(g,f[a.X]+h*(0-a.11.N.12),f[a.Y]+j*(e+i*(c*d+e)+(b*d)),h*k.P[i],j*d,$.K({1u:k.1B},a.1T(k.1n+\' \'+k.P[i])))}},2n:7(a,b,c,d,e,f,g){6 h=a.11;G(h){G(h.Q){a.E.14(a.M,f[a.X]+f[a.W]/2,f[a.Y]+f[a.H]+h.1d,h.Q,h.2w)}a.1I(h,\'V\',f[a.X],f[a.Y]+f[a.H],f[a.X]+f[a.W],f[a.Y]+f[a.H])}6 h=a.V;G(h.Q){a.E.14(a.M,0,0,h.Q,{19:\'1j\',2l:\'2d(\'+(f[a.X]-h.1d)+\',\'+(f[a.Y]+f[a.H]/2)+\') 2j(-2i)\'})}6 j=a.E.Z(a.M,\'11\',h.1D);6 k=a.E.Z(a.M,\'3f\',$.K({19:\'34\'},h.1C));a.E.1h(j,f[a.X],f[a.Y],f[a.X],f[a.Y]+f[a.H]);G(h.I.16){6 l=a.22(h,1O);O(6 i=1;i<c;i++){6 y=f[a.Y]+g*(e/2+i*(b*d+e));a.E.1h(j,f[a.X]+l[0]*h.I.1f,y,f[a.X]+l[1]*h.I.1f,y)}O(6 i=0;i<c;i++){6 y=f[a.Y]+g*(e/2+(i+0.5)*(b*d+e));a.E.14(k,f[a.X]-h.I.1f,y,(h.1i?h.1i[i]:\'\'+i))}}}});7 2E(){}$.K(2E.1r,{1q:7(){F\'3k 2G 1m\'},1Z:7(){F\'1Y 20 1s 1F 26 3g 2q 3j \'+\'2F 2I 2c 1E 2H O 2R 3i.\'},24:7(){F D},1R:7(a){6 b=a.1K(17,17);6 c=a.1x();G(a.1l[0]&&a.V.I.16){a.1X(b,a.2e(),1O,c,a.1l[0])}6 d=a.1g.27||10;6 e=a.1g.2a||10;6 f=a.U.J;6 g=(f?(a.U[0]).P.J:0);6 h=c[a.W];6 i=c[a.H]/((d+e)*g+e);4.1o=a.E.Z(a.M,\'1m\');4.3e(a,f,g,d,e,c,h,i);a.1M();a.E.14(a.M,c[a.X]+c[a.W]/2,c[a.Y]+c[a.H]+a.V.1d,1e.2m.28,{19:\'1j\'});a.1I(a.2e(),\'V\',c[a.X],c[a.Y]+c[a.H],c[a.X]+c[a.W],c[a.Y]+c[a.H]);4.3d(a,g,d,e,c,i);a.1W()},3e:7(a,b,c,d,e,f,h,j){6 k=a.2A();6 l=[];O(6 i=0;i<c;i++){l[i]=0}O(6 s=0;s<b;s++){6 m=a.U[s];6 g=a.E.Z(4.1o,\'1P\'+s,$.K({1c:m.1p,1y:m.1w},m.1z||{}));O(6 i=0;i<m.P.J;i++){a.E.1J(g,f[a.X]+h*l[i]/k[i],f[a.Y]+j*(e+i*(d+e)),h*m.P[i]/k[i],j*d,$.K({1u:m.1B},a.1T(m.1n+\' \'+2t(m.P[i]/k[i]*2g,2)+\'%\')));l[i]+=m.P[i]}}},3d:7(a,b,c,d,e,f){6 g=a.V;G(g.Q){a.E.14(a.M,0,0,g.Q,{19:\'1j\',2l:\'2d(\'+(e[a.X]-g.1d)+\',\'+(e[a.Y]+e[a.H]/2)+\') 2j(-2i)\'})}6 h=a.E.Z(a.M,\'11\',g.1D);6 j=a.E.Z(a.M,\'3f\',$.K({19:\'34\'},g.1C));a.E.1h(h,e[a.X],e[a.Y],e[a.X],e[a.Y]+e[a.H]);G(g.I.16){6 k=a.22(g,1O);O(6 i=1;i<b;i++){6 y=e[a.Y]+f*(d/2+i*(c+d));a.E.1h(h,e[a.X]+k[0]*g.I.1f,y,e[a.X]+k[1]*g.I.1f,y)}O(6 i=0;i<b;i++){6 y=e[a.Y]+f*(d/2+(i+0.5)*(c+d));a.E.14(j,e[a.X]-g.I.1f,y,(g.1i?g.1i[i]:\'\'+i))}}}});7 2D(){}$.K(2D.1r,{1q:7(){F\'2N 1h 1m\'},1Z:7(){F\'1Y 20 1s 1F 26 3I 3H.\'},24:7(){F[]},1R:7(a){a.1K();6 b=a.1x();6 c=b[a.W]/(a.V.N.1b-a.V.N.12);6 d=b[a.H]/(a.11.N.1b-a.11.N.12);4.1o=a.E.Z(a.M,\'1m\');O(6 i=0;i<a.U.J;i++){4.1H(a,i,b,c,d)}a.1M();a.2n();a.1W()},1H:7(a,b,c,d,e){6 f=a.U[b];6 g=a.E.3w();O(6 i=0;i<f.P.J;i++){6 x=c[a.X]+i*d;6 y=c[a.Y]+(a.11.N.1b-f.P[i])*e;G(i==0){g.3b(x,y)}3G{g.3a(x,y)}}a.E.3r(4.1o,g,$.K($.K({4F:\'1P\'+b,1u:\'3s\',1c:f.1p,1y:f.1w},a.1T(f.1n),f.1z||{})))}});7 38(){}$.K(38.1r,{3F:[\'37 (2b[]) - 4E 1s 4D 2c 37 2f 1s 1E 3D\',\'3C (2b) - 1E 3z 2c 4C 4B 4A 4z\',\'3y (2b) - 1E 3z 3o 4y O 4x 1F\'],1q:7(){F\'4w 1m\'},1Z:7(){F\'1Y 2F 4v 1s 1F 26 2I 2c 1E 2H.\'},24:7(){F 4.3F},1R:7(a){a.1K(17,17);4.1o=a.E.Z(a.M,\'1m\');6 b=a.1x();4.1H(a,b);a.1M();a.1W()},1H:7(a,b){6 c=a.2A();6 d=a.U.J;6 e=(d?(a.U[0]).P.J:0);6 f=a.E.3w();6 g=a.1g.37||[];6 h=a.1g.3C||10;6 l=(e<=1?0:a.1g.3y||10);6 m=(b[a.W]-(e*l)-l)/e/2;6 n=b[a.H]/2;6 o=13.12(m,n)-(g.J>0?h:0);6 p=a.E.Z(a.M,\'2L\',$.K({19:\'1j\'},a.V.1C));6 q=[];O(6 i=0;i<e;i++){6 r=b[a.X]+m+(i*(2*13.12(m,n)+l))+l;6 s=b[a.Y]+n;6 t=0;O(6 j=0;j<d;j++){6 u=a.U[j];G(i==0){q[j]=a.E.Z(4.1o,\'1P\'+j,$.K({1c:u.1p,1y:u.1w},u.1z||{}))}G(u.P[i]==0){4t}6 v=(t/c[i])*2*13.2C;t+=u.P[i];6 w=(t/c[i])*2*13.2C;6 z=1O;O(6 k=0;k<g.J;k++){G(g[k]==j){z=17;3c}}6 x=r+(z?h*13.33((v+w)/2):0);6 y=s+(z?h*13.32((v+w)/2):0);6 A=u.1n+\' \'+2t((w-v)/2/13.2C*2g,2)+\'%\';a.E.3r(q[j],f.4s().3b(x,y).3a(x+o*13.33(v),y+o*13.32(v)).4r(o,o,0,(w-v<13.2C?0:1),1,x+o*13.33(w),y+o*13.32(w)).4q(),$.K({1u:u.1B},a.1T(A)))}G(a.V){a.E.14(p,r,b[a.Y]+b[a.H]+a.V.1d,a.V.1i[i])}}}});7 2v(a){F(a.3A&&a.3A.3v().4p(/\\4o\\(\\)/))}1e.1U(\'30\',1k 2O());1e.1U(\'4n\',1k 2K());1e.1U(\'2G\',1k 2U());1e.1U(\'4m\',1k 2E());1e.1U(\'1h\',1k 2D());1e.1U(\'3D\',1k 38())})(4G)',62,291,'||||this||var|function|||||||||||||||||||||||||||||||||_root|return|if||_ticks|length|extend||_chartGroup|_scale|for|_values|_title||||_series|xAxis||||group||yAxis|min|Math|text||major|true|arguments|text_anchor|null|max|stroke|_titleOffset|svgGraphing|size|_chartOptions|line|_labels|middle|new|_gridlines|chart|_name|_chart|_stroke|title|prototype|of|legend|fill|typeof|_strokeWidth|_getDims|stroke_width|_settings|_drawGraph|_fill|_labelFormat|_lineFormat|the|values|_area|_drawSeries|_drawAxis|rect|_drawChartBackground|x2Axis|_drawTitle|y2Axis|false|series|minor|drawGraph|object|_showStatus|addChartType|_sampleSize|_drawLegend|_drawGridlines|Compare|description|sets|position|_getTickOffsets|_chartTypes|options||as|barWidth|percentageText|SVGGraphAxis|barGap|number|to|translate|_getPercentageAxis|out|100|offset|90|rotate|_onstatus|transform|region|_drawAxes|_drawXAxis|_width|bars|_drawNow|_chartType|roundNumber|_chartFormat|isArray|_titleFormat|_textSettings|_bgSettings|_show|_getTotals|settings|PI|SVGLineChart|SVGStackedRowChart|relative|row|whole|contributions|in|SVGStackedColumnChart|xAxisLabels|_height|Basic|SVGColumnChart|regional|parent|each|while|black|SVGRowChart|SVGGraphSeries|string|format|SVGGraphLegend|SVGGraphing|column|_percentageAxis|sin|cos|end|value|SVGGraph|explode|SVGPieChart|floor|lineTo|moveTo|break|_drawYAxis|_drawRows|yAxisLabels|horizontal|_drawColumns|category|showing|Stacked|grouped|with|vertical|between|firstChild|pow|path|none|labels|window|toString|createPath|both|pieGap|distance|constructor|categories|explodeDist|pie|graph|_options|else|lines|continuous|rows|Percentage|background|addExtension|appendChild|_svg|gap|bar|removeChild|width|status|round|redraw|textSettings|bgSettings|sampleSize|noDraw||area|show|svgManager|gray|valus|name|_axis|addSeries|chartTypes|green|gridlines|ticks|scale|_crossAt|onmouseout|chartArea|onmouseover|replace|chartFormat|chartOptions|chartType|stackedRow|stackedColumn|Array|match|close|arcTo|reset|continue|Labels|sizes|Pie|multiple|pies|section|exploded|an|move|sections|indexes|id|jQuery'.split('|'),0,{}))
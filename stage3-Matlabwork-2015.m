function [x]=stage3(A, b)
n=size(A); % variables 
n=n(1); % variables
A=[A b]; % variables
for i=1:n
    s=max(abs(A(i,:))); % get the max absolute value
    A(i,:)=A(i,:)/s;  %iterate over 
end

for k=1:n
    m=0;%the smallest posible absolute value for maximum
    pos=0;%the position of maximum in k column start from the diagonal
    for i=k:n%from diagonal to the bottom
        
       if abs(A(i,k))>m
          pos=i;
                       
          m=abs(A(i,k)); 
       end
    end
    
    if m==0
        x='Does not have full rank';  
        return;
    end
    if pos~=k  %position not k
        aux=A(k,:); %the row with the pivot  ##augment## using aux instead
        A(k,:)=A(pos,:);   % make k = pos
        A(pos,:)=aux;  % make new A = aux
    end
    % for every k value
    for i=(k+1):n
        A(i,:)=A(i,:)-A(k,:)*A(i,k)/A(k,k);  % row operation
    end
   
end
b=A(:,n+1);
A(:,n+1)=[]; % 
x=zeros(1,n); % avoid allocation error
if A(n,n)==0  % check if full rank
     x='Does not have full rank'; % 
        return;
end
x(n)=b(n,1)/A(n,n); % solve the equation annxn=bn
for i=n-1:-1:1% point to every x component
    s=0; 
    for j=i+1:n
      s=s+A(i,j)*x(j);%dot product a(n-1),(n-1)x(n-1)+a(n-1),nxn=b(n-1)=>a(n-1),(n-1)x(n-1)=b(n-1)-a(n-1),nxn
      %x(n-1)=[b(n-1)-a(n-1),nxn]/[a(n-1),(n-1)]
    end
    x(i)=(b(i,1)-s)/A(i,i);
   
end

end
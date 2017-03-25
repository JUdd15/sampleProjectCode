function [x]=stage2(A, b)
n=size(A); % variables for A 
n=n(1); % variable for n 
A=[A b]; % variable for A 


for k=1:n-1
    m=0; %the smallest possible absolute value for maximum
 
    for i=k:n %from diagonal to the bottom
       if abs(A(k,i))>m
       
          m=abs(A(k,i)); 
       end
    end
    
    if m==0
        x='Does not have full rank';
        return;
    end
   
    for i=(k+1):n
        A(i,:)=A(i,:)-A(k,:)*A(i,k)/A(k,k);
    end
   
end

b=A(:,n+1);  % adding b matrix

A(:,n+1)=[];  % clear last column
x=zeros(1,n); %pre allocation matrix
if A(n,n)==0  % condition for A nn matrix
     x='No unique soluton'; 
        return;
end
x(n)=b(n,1)/A(n,n); % backward substitution
for i=n-1:-1:1  
        x(i)=b(i);
    for j=i+1:n
        x(i)=x(i)-A(i,j)*x(j);
        
    end
    x(i)=x(i)/A(i,i);
end

end
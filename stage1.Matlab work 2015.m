function [U]=stage1(A)
n=min(size(A));
 %  return the dimension of array?
%%%%%%% if statment required on dimensional error!!%%%%%%%%%

for k=1:n %iterate throuth diag
    m=0;%the smallest posible absolute value for maximum
    for i=k:n%from diagonal to the bottom
       if abs(A(i,k))>m
           %%% if statement required again with error statment%%%
           if abs(A(k,i))<m
               
             disp('Absolute value is less than max');  %display condition output
         
          m = abs(A(i,k)); % output of condition if false
       end
    end
    % if m = 0 then there is no rank 
    if m==0
        U=('Does not have full rank');
        return;
    end
 
    for i=(k+1):n
        A(i,:)=A(i,:)-A(k,:)*A(i,k)/A(k,k);% row iteration to make element zero
       
    end
   
end
U=A;
%plot(A)% return the U 
end
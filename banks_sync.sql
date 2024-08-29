

UPDATE `ladolnewdb`.banks
SET bank_name = 'ECO BANK',
    bankname = 'ECO BANK'  WHERE id IN (1);
    
UPDATE `ladolnewdb`.banks
SET bank_name = 'FCMB',
    bankname = 'FCMB'  WHERE id IN (2);
    
UPDATE `ladolnewdb`.banks
SET bank_name = 'ACCESS BANK',
    bankname = 'ACCESS BANK'  WHERE id IN (3);

UPDATE `ladolnewdb`.banks
SET bank_name = 'STANBIC IBTC',
    bankname = 'STANBIC IBTC'  WHERE id IN (4);    
    
UPDATE `ladolnewdb`.banks
SET bank_name = 'UBA',
    bankname = 'UBA'  WHERE id IN (5);   

UPDATE `ladolnewdb`.banks
SET bank_name = 'FIRST BANK',
    bankname = 'FIRST BANK'  WHERE id IN (6);  
    
SELECT * FROM `ladolnewdb`.banks;
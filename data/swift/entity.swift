
//
//  AgencyCoda
//
//  Created by Matias Camiletti on 10/8/20.
//  Copyright © 2020 AgencyCoda. All rights reserved.
//

import UIKit

class %%nameClass%%: NSObject, Decodable {
    
%%properties%%
    
    enum %%nameClass%%CodingKey : String, CodingKey {
%%coding%%
    }
    
    required convenience init(from decoder: Decoder) throws {
        self.init();
        let container = try decoder.container(keyedBy: %%nameClass%%CodingKey.self);
%%init%%
    }
}
